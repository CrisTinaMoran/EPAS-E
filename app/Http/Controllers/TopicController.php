<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\InformationSheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AnnouncementController;


class TopicController extends Controller
{
    public function create($informationSheetId)
    {
        $informationSheet = InformationSheet::with(['module.course'])->findOrFail($informationSheetId);
        $nextOrder = $informationSheet->topics()->max('order') + 1;
        
        return view('modules.information-sheets.topics.create', compact('informationSheet', 'nextOrder'));
    }

    public function store(Request $request, $informationSheetId)
    {
        // Debug: Log the request
        Log::info('Topic store method called', [
            'information_sheet_id' => $informationSheetId,
            'request_data' => $request->all()
        ]);

        $informationSheet = InformationSheet::findOrFail($informationSheetId);
        
        $validated = $request->validate([
            'topic_number' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'required|integer|min:0',
        ]);

        try {
            Log::info('Validation passed', ['validated_data' => $validated]);

            // Use HTML Purifier for maximum security (without nl2br)
            $validated['content'] = $this->sanitizeWithHtmlPurifier($validated['content']);
            
            Log::info('Content sanitized successfully');

            $topic = $informationSheet->topics()->create($validated);
            
            // Load relationships for the announcement
            $informationSheet->load('module.course');
            $module = $informationSheet->module;
            $course = $module->course;
            
            $content = "New topic '{$topic->title}' (Topic {$topic->topic_number}) has been added to Information Sheet {$informationSheet->sheet_number} in Module {$module->module_number} of {$course->course_name}.";
            
            // Fix: Use the full class reference
            \App\Http\Controllers\AnnouncementController::createAutomaticAnnouncement(
                'topic', 
                $content, 
                auth()->user(), 
                'all' 
            );
            
            Log::info('Topic created and announcement sent');

            return redirect()->route('courses.index')
                ->with('success', "Topic '{$topic->title}' created successfully!");

        } catch (\Exception $e) {
            Log::error('Topic creation failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()->withInput()
                ->with('error', 'Failed to create topic: ' . $e->getMessage());
        }
    }

    public function edit($informationSheetId, $topicId)
    {
        $informationSheet = InformationSheet::with(['module.course'])->findOrFail($informationSheetId);
        $topic = Topic::findOrFail($topicId);
        
        return view('modules.information-sheets.topics.edit', compact('informationSheet', 'topic'));
    }

    public function update(Request $request, $informationSheetId, $topicId)
    {
        $informationSheet = InformationSheet::findOrFail($informationSheetId);
        $topic = Topic::findOrFail($topicId);
        
        $validated = $request->validate([
            'topic_number' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'required|integer|min:0',
        ]);

        try {
            // Use HTML Purifier for maximum security (without nl2br)
            $validated['content'] = $this->sanitizeWithHtmlPurifier($validated['content']);

            $topic->update($validated);

            return redirect()->route('courses.index')
                ->with('success', "Topic '{$topic->title}' updated successfully!");

        } catch (\Exception $e) {
            Log::error('Topic update failed: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Failed to update topic. Please try again.');
        }
    }

    public function destroy($topicId)
    {
        try {
            $topic = Topic::findOrFail($topicId);
            $topicTitle = $topic->title;
            $topic->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => "Topic '{$topicTitle}' deleted successfully!"
                ]);
            }

            return redirect()->route('courses.index')
                ->with('success', "Topic '{$topicTitle}' deleted successfully!");

        } catch (\Exception $e) {
            Log::error('Topic deletion failed: ' . $e->getMessage());
            
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Failed to delete topic. Please try again.'
                ], 500);
            }
            
            return back()->with('error', 'Failed to delete topic. Please try again.');
        }
    }

    public function showContent($topicId)
    {
        $topic = Topic::with('informationSheet.module')->findOrFail($topicId);
        return view('modules.information-sheets.topics.content', compact('topic'));
    }

    public function getContent(Topic $topic)
    {
        try {
            // Return the topic content as HTML for AJAX requests
            $html = view('modules.information-sheets.topics.content-partial', compact('topic'))->render();
            
            return response()->json([
                'html' => $html
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading topic content: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to load topic content'
            ], 500);
        }
    }

    /**
     * Most Secure: Using HTML Purifier
     * Protects against all known XSS attacks while allowing basic formatting
     */
    private function sanitizeWithHtmlPurifier($content)
    {
        // Check if HTML Purifier is available
        if (!class_exists('HTMLPurifier')) {
            Log::warning('HTMLPurifier not found, using fallback sanitization');
            // Fallback to basic security if HTML Purifier isn't installed
            return $this->basicFallbackSanitize($content);
        }

        try {
            $config = \HTMLPurifier_Config::createDefault();
            
            // Only allow basic formatting tags
            $config->set('HTML.Allowed', 'b,strong,i,em,u,br,p,ul,ol,li,code');
            
            // No attributes allowed for maximum security
            $config->set('HTML.AllowedAttributes', '');
            
            // Disable auto-formatting to preserve user's intended formatting
            $config->set('AutoFormat.AutoParagraph', false);
            $config->set('AutoFormat.Linkify', false);
            $config->set('AutoFormat.RemoveEmpty', false);
            
            // Preserve newlines in the source
            $config->set('Core.NormalizeNewlines', false);
            $config->set('Core.CollectErrors', false);
            
            $purifier = new \HTMLPurifier($config);
            $cleaned = $purifier->purify($content);
            
            // DON'T convert newlines to <br> tags here - store raw content
            // The conversion will happen only when displaying to users
            return $cleaned;
            
        } catch (\Exception $e) {
            Log::error('HTMLPurifier error: ' . $e->getMessage());
            return $this->basicFallbackSanitize($content);
        }
    }

    /**
     * Basic fallback sanitization if HTML Purifier is not available
     * Still provides good security but not as comprehensive as HTML Purifier
     */
    private function basicFallbackSanitize($content)
    {
        // Remove NULL bytes
        $content = str_replace("\0", '', $content);
        
        // Convert all special characters to HTML entities
        $content = htmlspecialchars($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Define allowed tags and their safe replacements
        $allowedTags = [
            'b' => '&lt;b&gt;',
            'strong' => '&lt;strong&gt;',
            'i' => '&lt;i&gt;', 
            'em' => '&lt;em&gt;',
            'u' => '&lt;u&gt;',
            'br' => '&lt;br&gt;',
            'p' => '&lt;p&gt;',
            'ul' => '&lt;ul&gt;',
            'ol' => '&lt;ol&gt;',
            'li' => '&lt;li&gt;',
            'code' => '&lt;code&gt;'
        ];
        
        $closingTags = [
            'b' => '&lt;/b&gt;',
            'strong' => '&lt;/strong&gt;',
            'i' => '&lt;/i&gt;',
            'em' => '&lt;/em&gt;',
            'u' => '&lt;/u&gt;',
            'p' => '&lt;/p&gt;',
            'ul' => '&lt;/ul&gt;',
            'ol' => '&lt;/ol&gt;',
            'li' => '&lt;/li&gt;',
            'code' => '&lt;/code&gt;'
        ];
        
        // Restore allowed opening tags
        foreach ($allowedTags as $tag => $entity) {
            $content = str_replace($entity, "<$tag>", $content);
        }
        
        // Restore allowed closing tags
        foreach ($closingTags as $tag => $entity) {
            $content = str_replace($entity, "</$tag>", $content);
        }
        
        // DON'T convert newlines to <br> tags here either
        return $content;
    }
}