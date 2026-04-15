<?php
/**
 * Apex Notes Profanity Filter
 */

if (!defined('ABSPATH')) {
    exit;
}

class Apex_Notes_Profanity {
    
    /**
     * List of profane words to filter
     */
    private static $words = array(
        'fuck', 'shit', 'ass', 'bitch', 'damn', 'crap', 'dick', 'cock', 'pussy', 'cunt',
        'bastard', 'whore', 'slut', 'fag', 'nigger', 'retard', 'idiot', 'moron',
        'asshole', 'bullshit', 'dumbass', 'jackass', 'piss', 'douche', 'twat',
        'wanker', 'bollocks', 'arse', 'bloody', 'bugger', 'sodding', 'shite',
        'motherfucker', 'fucker', 'fuckhead', 'dickhead', 'shithead', 'arsehole',
        // Add more words as needed
    );
    
    /**
     * Check if content contains profanity
     */
    public static function check_content($data) {
        $fields_to_check = array();
        
        // Collect all text fields
        if (isset($data['title'])) $fields_to_check[] = $data['title'];
        if (isset($data['description'])) $fields_to_check[] = $data['description'];
        if (isset($data['setup_notes'])) $fields_to_check[] = $data['setup_notes'];
        
        // Check sections
        if (isset($data['sections']) && is_array($data['sections'])) {
            foreach ($data['sections'] as $section) {
                if (isset($section['name'])) $fields_to_check[] = $section['name'];
                if (isset($section['braking'])) $fields_to_check[] = $section['braking'];
                if (isset($section['turn_in'])) $fields_to_check[] = $section['turn_in'];
                if (isset($section['apex'])) $fields_to_check[] = $section['apex'];
                if (isset($section['exit'])) $fields_to_check[] = $section['exit'];
                if (isset($section['tip'])) $fields_to_check[] = $section['tip'];
                if (isset($section['pro_tip'])) $fields_to_check[] = $section['pro_tip'];
            }
        }
        
        // Check each field
        foreach ($fields_to_check as $text) {
            if (self::contains_profanity($text)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if text contains profanity
     */
    public static function contains_profanity($text) {
        $text = strtolower($text);
        
        foreach (self::$words as $word) {
            // Match whole words only
            $pattern = '/\b' . preg_quote($word, '/') . '\b/i';
            if (preg_match($pattern, $text)) {
                return true;
            }
            
            // Also check for leetspeak variations
            $leetspeak = self::convert_to_leetspeak($word);
            if ($leetspeak !== $word) {
                $pattern = '/\b' . preg_quote($leetspeak, '/') . '\b/i';
                if (preg_match($pattern, $text)) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Convert word to leetspeak pattern
     */
    private static function convert_to_leetspeak($word) {
        $replacements = array(
            'a' => '[a@4]',
            'e' => '[e3]',
            'i' => '[i1!]',
            'o' => '[o0]',
            's' => '[s$5]',
            't' => '[t7+]',
            'l' => '[l1|]',
        );
        
        return str_replace(array_keys($replacements), array_values($replacements), $word);
    }
    
    /**
     * Filter profanity from text
     */
    public static function filter_text($text) {
        foreach (self::$words as $word) {
            $pattern = '/\b' . preg_quote($word, '/') . '\b/i';
            $replacement = str_repeat('*', strlen($word));
            $text = preg_replace($pattern, $replacement, $text);
        }
        
        return $text;
    }
    
    /**
     * Get list of profane words found in text
     */
    public static function get_found_words($text) {
        $found = array();
        $text = strtolower($text);
        
        foreach (self::$words as $word) {
            $pattern = '/\b' . preg_quote($word, '/') . '\b/i';
            if (preg_match($pattern, $text)) {
                $found[] = $word;
            }
        }
        
        return $found;
    }
    
    /**
     * Add custom words to filter
     */
    public static function add_words($words) {
        if (is_array($words)) {
            self::$words = array_merge(self::$words, $words);
        } else {
            self::$words[] = $words;
        }
    }
    
    /**
     * Get all filtered words
     */
    public static function get_words() {
        return self::$words;
    }
}
