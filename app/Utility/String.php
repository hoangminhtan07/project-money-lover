<?php
  /**
  3:  * String handling methods.
  4:  *
  5:  * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
  6:  * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
  7:  *
  8:  * Licensed under The MIT License
  9:  * For full copyright and license information, please see the LICENSE.txt
 10:  * Redistributions of files must retain the above copyright notice.
 11:  *
 12:  * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 13:  * @link          http://cakephp.org CakePHP(tm) Project
 14:  * @package       Cake.Utility
 15:  * @since         CakePHP(tm) v 1.2.0.5551
 16:  * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 17:  */
  
  /**
 20:  * String handling methods.
 21:  *
 22:  * @package       Cake.Utility
 23:  */
  class String {
  
  /**
 27:  * Generate a random UUID
 28:  *
 29:  * @see http://www.ietf.org/rfc/rfc4122.txt
 30:  * @return RFC 4122 UUID
 31:  */
      public static function uuid() {
          $node = env('SERVER_ADDR');
  
          if (strpos($node, ':') !== false) {
              if (substr_count($node, '::')) {
                  $node = str_replace(
                      '::', str_repeat(':0000', 8 - substr_count($node, ':')) . ':', $node
                  );
              }
              $node = explode(':', $node);
              $ipSix = '';
  
              foreach ($node as $id) {
                  $ipSix .= str_pad(base_convert($id, 16, 2), 16, 0, STR_PAD_LEFT);
              }
              $node = base_convert($ipSix, 2, 10);
  
              if (strlen($node) < 38) {
                  $node = null;
              } else {
                  $node = crc32($node);
              }
          } elseif (empty($node)) {
              $host = env('HOSTNAME');
  
              if (empty($host)) {
                  $host = env('HOST');
              }
  
              if (!empty($host)) {
                 $ip = gethostbyname($host);
 
                  if ($ip === $host) {
                      $node = crc32($host);
                  } else {
                      $node = ip2long($ip);
                  }
              }
          } elseif ($node !== '127.0.0.1') {
              $node = ip2long($node);
         } else {
              $node = null;
          }
  
          if (empty($node)) {
              $node = crc32(Configure::read('Security.salt'));
          }
  
          if (function_exists('hphp_get_thread_id')) {
              $pid = hphp_get_thread_id();
          } elseif (function_exists('zend_thread_id')) {
             $pid = zend_thread_id();
          } else {
              $pid = getmypid();
          }
  
          if (!$pid || $pid > 65535) {
              $pid = mt_rand(0, 0xfff) | 0x4000;
          }
  
          list($timeMid, $timeLow) = explode(' ', microtime());
          return sprintf(
             "%08x-%04x-%04x-%02x%02x-%04x%08x", (int)$timeLow, (int)substr($timeMid, 2) & 0xffff,
             mt_rand(0, 0xfff) | 0x4000, mt_rand(0, 0x3f) | 0x80, mt_rand(0, 0xff), $pid, $node
          );
      }
  
  /**
100:  * Tokenizes a string using $separator, ignoring any instance of $separator that appears between
101:  * $leftBound and $rightBound.
102:  *
103:  * @param string $data The data to tokenize.
104:  * @param string $separator The token to split the data on.
105:  * @param string $leftBound The left boundary to ignore separators in.
106:  * @param string $rightBound The right boundary to ignore separators in.
107:  * @return mixed Array of tokens in $data or original input if empty.
108:  */
     public static function tokenize($data, $separator = ',', $leftBound = '(', $rightBound = ')') {
         if (empty($data)) {
             return array();
         }
 
         $depth = 0;
         $offset = 0;
         $buffer = '';
         $results = array();
         $length = strlen($data);
         $open = false;
 
         while ($offset <= $length) {
             $tmpOffset = -1;
             $offsets = array(
                 strpos($data, $separator, $offset),
                 strpos($data, $leftBound, $offset),
                 strpos($data, $rightBound, $offset)
             );
             for ($i = 0; $i < 3; $i++) {
                 if ($offsets[$i] !== false && ($offsets[$i] < $tmpOffset || $tmpOffset == -1)) {
                     $tmpOffset = $offsets[$i];
                 }
             }
             if ($tmpOffset !== -1) {
                 $buffer .= substr($data, $offset, ($tmpOffset - $offset));
                 if (!$depth && $data{$tmpOffset} === $separator) {
                     $results[] = $buffer;
                     $buffer = '';
                 } else {
                     $buffer .= $data{$tmpOffset};
                 }
                 if ($leftBound !== $rightBound) {
                     if ($data{$tmpOffset} === $leftBound) {
                         $depth++;
                     }
                     if ($data{$tmpOffset} === $rightBound) {
                         $depth--;
                     }
                 } else {
                     if ($data{$tmpOffset} === $leftBound) {
                         if (!$open) {
                             $depth++;
                             $open = true;
                        } else {
                             $depth--;
                         }
                     }
                 }
                 $offset = ++$tmpOffset;
             } else {
                 $results[] = $buffer . substr($data, $offset);
                 $offset = $length + 1;
             }
         }
         if (empty($results) && !empty($buffer)) {
             $results[] = $buffer;
         }
 
         if (!empty($results)) {
             return array_map('trim', $results);
         }
 
         return array();
     }
 
 /**
176:  * Replaces variable placeholders inside a $str with any given $data. Each key in the $data array
177:  * corresponds to a variable placeholder name in $str.
178:  * Example: `String::insert(':name is :age years old.', array('name' => 'Bob', '65'));`
179:  * Returns: Bob is 65 years old.
180:  *
181:  * Available $options are:
182:  *
183:  * - before: The character or string in front of the name of the variable placeholder (Defaults to `:`)
184:  * - after: The character or string after the name of the variable placeholder (Defaults to null)
185:  * - escape: The character or string used to escape the before character / string (Defaults to `\`)
186:  * - format: A regex to use for matching variable placeholders. Default is: `/(?<!\\)\:%s/`
187:  *   (Overwrites before, after, breaks escape / clean)
188:  * - clean: A boolean or array with instructions for String::cleanInsert
189:  *
190:  * @param string $str A string containing variable placeholders
191:  * @param array $data A key => val array where each key stands for a placeholder variable name
192:  *     to be replaced with val
193:  * @param array $options An array of options, see description above
194:  * @return string
195:  */
     public static function insert($str, $data, $options = array()) {
         $defaults = array(
             'before' => ':', 'after' => null, 'escape' => '\\', 'format' => null, 'clean' => false
         );
         $options += $defaults;
         $format = $options['format'];
         $data = (array)$data;
         if (empty($data)) {
            return ($options['clean']) ? String::cleanInsert($str, $options) : $str;
         }
 
         if (!isset($format)) {
             $format = sprintf(
                 '/(?<!%s)%s%%s%s/',
                 preg_quote($options['escape'], '/'),
                 str_replace('%', '%%', preg_quote($options['before'], '/')),
                 str_replace('%', '%%', preg_quote($options['after'], '/'))
             );
         }
 
         if (strpos($str, '?') !== false && is_numeric(key($data))) {
             $offset = 0;
             while (($pos = strpos($str, '?', $offset)) !== false) {
                 $val = array_shift($data);
                 $offset = $pos + strlen($val);
                 $str = substr_replace($str, $val, $pos, 1);
             }
             return ($options['clean']) ? String::cleanInsert($str, $options) : $str;
         }
 
         asort($data);
 
         $dataKeys = array_keys($data);
         $hashKeys = array_map('crc32', $dataKeys);
         $tempData = array_combine($dataKeys, $hashKeys);
         krsort($tempData);
 
         foreach ($tempData as $key => $hashVal) {
             $key = sprintf($format, preg_quote($key, '/'));
             $str = preg_replace($key, $hashVal, $str);
         }
         $dataReplacements = array_combine($hashKeys, array_values($data));
         foreach ($dataReplacements as $tmpHash => $tmpValue) {
             $tmpValue = (is_array($tmpValue)) ? '' : $tmpValue;
             $str = str_replace($tmpHash, $tmpValue, $str);
         }
 
         if (!isset($options['format']) && isset($options['before'])) {
             $str = str_replace($options['escape'] . $options['before'], $options['before'], $str);
         }
         return ($options['clean']) ? String::cleanInsert($str, $options) : $str;
     }
 
 /**
250:  * Cleans up a String::insert() formatted string with given $options depending on the 'clean' key in
251:  * $options. The default method used is text but html is also available. The goal of this function
252:  * is to replace all whitespace and unneeded markup around placeholders that did not get replaced
253:  * by String::insert().
254:  *
255:  * @param string $str String to clean.
256:  * @param array $options Options list.
257:  * @return string
258:  * @see String::insert()
259:  */
     public static function cleanInsert($str, $options) {
         $clean = $options['clean'];
         if (!$clean) {
             return $str;
         }
         if ($clean === true) {
             $clean = array('method' => 'text');
         }
         if (!is_array($clean)) {
            $clean = array('method' => $options['clean']);
         }
         switch ($clean['method']) {
             case 'html':
                 $clean = array_merge(array(
                    'word' => '[\w,.]+',
                     'andText' => true,
                     'replacement' => '',
                 ), $clean);
                 $kleenex = sprintf(
                     '/[\s]*[a-z]+=(")(%s%s%s[\s]*)+\\1/i',
                     preg_quote($options['before'], '/'),
                     $clean['word'],
                     preg_quote($options['after'], '/')
                 );
                 $str = preg_replace($kleenex, $clean['replacement'], $str);
                 if ($clean['andText']) {
                     $options['clean'] = array('method' => 'text');
                     $str = String::cleanInsert($str, $options);
                 }
                 break;
             case 'text':
                 $clean = array_merge(array(
                     'word' => '[\w,.]+',
                     'gap' => '[\s]*(?:(?:and|or)[\s]*)?',
                     'replacement' => '',
                 ), $clean);
 
                 $kleenex = sprintf(
                     '/(%s%s%s%s|%s%s%s%s)/',
                     preg_quote($options['before'], '/'),
                     $clean['word'],
                     preg_quote($options['after'], '/'),
                     $clean['gap'],
                     $clean['gap'],
                     preg_quote($options['before'], '/'),
                     $clean['word'],
                     preg_quote($options['after'], '/')
                 );
                 $str = preg_replace($kleenex, $clean['replacement'], $str);
                 break;
         }
         return $str;
     }
 
 /**
315:  * Wraps text to a specific width, can optionally wrap at word breaks.
316:  *
317:  * ### Options
318:  *
319:  * - `width` The width to wrap to. Defaults to 72.
320:  * - `wordWrap` Only wrap on words breaks (spaces) Defaults to true.
321:  * - `indent` String to indent with. Defaults to null.
322:  * - `indentAt` 0 based index to start indenting at. Defaults to 0.
323:  *
324:  * @param string $text The text to format.
325:  * @param array|int $options Array of options to use, or an integer to wrap the text to.
326:  * @return string Formatted text.
327:  */
     public static function wrap($text, $options = array()) {
         if (is_numeric($options)) {
             $options = array('width' => $options);
         }
         $options += array('width' => 72, 'wordWrap' => true, 'indent' => null, 'indentAt' => 0);
         if ($options['wordWrap']) {
             $wrapped = self::wordWrap($text, $options['width'], "\n");
         } else {
             $wrapped = trim(chunk_split($text, $options['width'] - 1, "\n"));
         }
         if (!empty($options['indent'])) {
             $chunks = explode("\n", $wrapped);
             for ($i = $options['indentAt'], $len = count($chunks); $i < $len; $i++) {
                 $chunks[$i] = $options['indent'] . $chunks[$i];
             }
             $wrapped = implode("\n", $chunks);
         }
         return $wrapped;
     }
 
 /**
349:  * Unicode and newline aware version of wordwrap.
350:  *
351:  * @param string $text The text to format.
352:  * @param int $width The width to wrap to. Defaults to 72.
353:  * @param string $break The line is broken using the optional break parameter. Defaults to '\n'.
354:  * @param bool $cut If the cut is set to true, the string is always wrapped at the specified width.
355:  * @return string Formatted text.
356:  */
     public static function wordWrap($text, $width = 72, $break = "\n", $cut = false) {
         $paragraphs = explode($break, $text);
         foreach ($paragraphs as &$paragraph) {
             $paragraph = String::_wordWrap($paragraph, $width, $break, $cut);
         }
         return implode($break, $paragraphs);
     }
 
 /**
366:  * Unicode aware version of wordwrap as helper method.
367:  *
368:  * @param string $text The text to format.
369:  * @param int $width The width to wrap to. Defaults to 72.
370:  * @param string $break The line is broken using the optional break parameter. Defaults to '\n'.
371:  * @param bool $cut If the cut is set to true, the string is always wrapped at the specified width.
372:  * @return string Formatted text.
373:  */
     protected static function _wordWrap($text, $width = 72, $break = "\n", $cut = false) {
         if ($cut) {
             $parts = array();
             while (mb_strlen($text) > 0) {
                 $part = mb_substr($text, 0, $width);
                 $parts[] = trim($part);
                 $text = trim(mb_substr($text, mb_strlen($part)));
             }
             return implode($break, $parts);
         }
 
         $parts = array();
         while (mb_strlen($text) > 0) {
             if ($width >= mb_strlen($text)) {
                 $parts[] = trim($text);
                 break;
             }
 
             $part = mb_substr($text, 0, $width);
             $nextChar = mb_substr($text, $width, 1);
             if ($nextChar !== ' ') {
                 $breakAt = mb_strrpos($part, ' ');
                 if ($breakAt === false) {
                     $breakAt = mb_strpos($text, ' ', $width);
                 }
                 if ($breakAt === false) {
                     $parts[] = trim($text);
                     break;
                 }
                 $part = mb_substr($text, 0, $breakAt);
             }
 
             $part = trim($part);
             $parts[] = $part;
             $text = trim(mb_substr($text, mb_strlen($part)));
         }
 
         return implode($break, $parts);
     }
 
 /**
415:  * Highlights a given phrase in a text. You can specify any expression in highlighter that
416:  * may include the \1 expression to include the $phrase found.
417:  *
418:  * ### Options:
419:  *
420:  * - `format` The piece of html with that the phrase will be highlighted
421:  * - `html` If true, will ignore any HTML tags, ensuring that only the correct text is highlighted
422:  * - `regex` a custom regex rule that is used to match words, default is '|$tag|iu'
423:  *
424:  * @param string $text Text to search the phrase in.
425:  * @param string|array $phrase The phrase or phrases that will be searched.
426:  * @param array $options An array of html attributes and options.
427:  * @return string The highlighted text
428:  * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/text.html#TextHelper::highlight
429:  */
     public static function highlight($text, $phrase, $options = array()) {
         if (empty($phrase)) {
             return $text;
         }
 
         $defaults = array(
             'format' => '<span class="highlight">\1</span>',
             'html' => false,
             'regex' => "|%s|iu"
         );
         $options += $defaults;
         extract($options);
 
         if (is_array($phrase)) {
             $replace = array();
             $with = array();
 
             foreach ($phrase as $key => $segment) {
                 $segment = '(' . preg_quote($segment, '|') . ')';
                 if ($html) {
                     $segment = "(?![^<]+>)$segment(?![^<]+>)";
                 }
 
                 $with[] = (is_array($format)) ? $format[$key] : $format;
                 $replace[] = sprintf($options['regex'], $segment);
             }
 
             return preg_replace($replace, $with, $text);
         }
 
         $phrase = '(' . preg_quote($phrase, '|') . ')';
         if ($html) {
             $phrase = "(?![^<]+>)$phrase(?![^<]+>)";
         }
 
         return preg_replace(sprintf($options['regex'], $phrase), $format, $text);
     }
 
 /**
469:  * Strips given text of all links (<a href=....).
470:  *
471:  * @param string $text Text
472:  * @return string The text without links
473:  * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/text.html#TextHelper::stripLinks
474:  */
     public static function stripLinks($text) {
         return preg_replace('|<a\s+[^>]+>|im', '', preg_replace('|<\/a>|im', '', $text));
     }
 
 /**
480:  * Truncates text starting from the end.
481:  *
482:  * Cuts a string to the length of $length and replaces the first characters
483:  * with the ellipsis if the text is longer than length.
484:  *
485:  * ### Options:
486:  *
487:  * - `ellipsis` Will be used as Beginning and prepended to the trimmed string
488:  * - `exact` If false, $text will not be cut mid-word
489:  *
490:  * @param string $text String to truncate.
491:  * @param int $length Length of returned string, including ellipsis.
492:  * @param array $options An array of options.
493:  * @return string Trimmed string.
494:  */
     public static function tail($text, $length = 100, $options = array()) {
         $defaults = array(
             'ellipsis' => '...', 'exact' => true
         );
         $options += $defaults;
         extract($options);
 
         if (!function_exists('mb_strlen')) {
             class_exists('Multibyte');
         }
 
         if (mb_strlen($text) <= $length) {
             return $text;
         }
 
         $truncate = mb_substr($text, mb_strlen($text) - $length + mb_strlen($ellipsis));
         if (!$exact) {
             $spacepos = mb_strpos($truncate, ' ');
             $truncate = $spacepos === false ? '' : trim(mb_substr($truncate, $spacepos));
         }
 
         return $ellipsis . $truncate;
     }
 
 /**
520:  * Truncates text.
521:  *
522:  * Cuts a string to the length of $length and replaces the last characters
523:  * with the ellipsis if the text is longer than length.
524:  *
525:  * ### Options:
526:  *
527:  * - `ellipsis` Will be used as Ending and appended to the trimmed string (`ending` is deprecated)
528:  * - `exact` If false, $text will not be cut mid-word
529:  * - `html` If true, HTML tags would be handled correctly
530:  *
531:  * @param string $text String to truncate.
532:  * @param int $length Length of returned string, including ellipsis.
533:  * @param array $options An array of html attributes and options.
534:  * @return string Trimmed string.
535:  * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/text.html#TextHelper::truncate
536:  */
     public static function truncate($text, $length = 100, $options = array()) {
         $defaults = array(
             'ellipsis' => '...', 'exact' => true, 'html' => false
         );
         if (isset($options['ending'])) {
             $defaults['ellipsis'] = $options['ending'];
         } elseif (!empty($options['html']) && Configure::read('App.encoding') === 'UTF-8') {
             $defaults['ellipsis'] = "\xe2\x80\xa6";
         }
         $options += $defaults;
         extract($options);
 
         if (!function_exists('mb_strlen')) {
             class_exists('Multibyte');
         }
 
         if ($html) {
             if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                 return $text;
             }
             $totalLength = mb_strlen(strip_tags($ellipsis));
             $openTags = array();
             $truncate = '';
 
             preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
             foreach ($tags as $tag) {
                 if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
                     if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
                         array_unshift($openTags, $tag[2]);
                     } elseif (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
                         $pos = array_search($closeTag[1], $openTags);
                         if ($pos !== false) {
                             array_splice($openTags, $pos, 1);
                         }
                     }
                 }
                 $truncate .= $tag[1];
 
                 $contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
                 if ($contentLength + $totalLength > $length) {
                     $left = $length - $totalLength;
                     $entitiesLength = 0;
                     if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
                         foreach ($entities[0] as $entity) {
                             if ($entity[1] + 1 - $entitiesLength <= $left) {
                                 $left--;
                                 $entitiesLength += mb_strlen($entity[0]);
                             } else {
                                 break;
                             }
                         }
                     }
 
                     $truncate .= mb_substr($tag[3], 0, $left + $entitiesLength);
                     break;
                 } else {
                     $truncate .= $tag[3];
                     $totalLength += $contentLength;
                 }
                if ($totalLength >= $length) {
                     break;
                 }
             }
         } else {
             if (mb_strlen($text) <= $length) {
                 return $text;
             }
             $truncate = mb_substr($text, 0, $length - mb_strlen($ellipsis));
         }
         if (!$exact) {
             $spacepos = mb_strrpos($truncate, ' ');
             if ($html) {
                 $truncateCheck = mb_substr($truncate, 0, $spacepos);
                 $lastOpenTag = mb_strrpos($truncateCheck, '<');
                 $lastCloseTag = mb_strrpos($truncateCheck, '>');
                 if ($lastOpenTag > $lastCloseTag) {
                     preg_match_all('/<[\w]+[^>]*>/s', $truncate, $lastTagMatches);
                     $lastTag = array_pop($lastTagMatches[0]);
                     $spacepos = mb_strrpos($truncate, $lastTag) + mb_strlen($lastTag);
                 }
                 $bits = mb_substr($truncate, $spacepos);
                 preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
                 if (!empty($droppedTags)) {
                     if (!empty($openTags)) {
                         foreach ($droppedTags as $closingTag) {
                             if (!in_array($closingTag[1], $openTags)) {
                                 array_unshift($openTags, $closingTag[1]);
                             }
                         }
                     } else {
                         foreach ($droppedTags as $closingTag) {
                             $openTags[] = $closingTag[1];
                         }
                     }
                 }
             }
             $truncate = mb_substr($truncate, 0, $spacepos);
         }
         $truncate .= $ellipsis;
 
         if ($html) {
             foreach ($openTags as $tag) {
                 $truncate .= '</' . $tag . '>';
             }
         }
 
         return $truncate;
     }
 
 /**
647:  * Extracts an excerpt from the text surrounding the phrase with a number of characters on each side
648:  * determined by radius.
649:  *
650:  * @param string $text String to search the phrase in
651:  * @param string $phrase Phrase that will be searched for
652:  * @param int $radius The amount of characters that will be returned on each side of the founded phrase
653:  * @param string $ellipsis Ending that will be appended
654:  * @return string Modified string
655:  * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/text.html#TextHelper::excerpt
656:  */
     public static function excerpt($text, $phrase, $radius = 100, $ellipsis = '...') {
         if (empty($text) || empty($phrase)) {
             return self::truncate($text, $radius * 2, array('ellipsis' => $ellipsis));
         }
 
         $append = $prepend = $ellipsis;
 
         $phraseLen = mb_strlen($phrase);
         $textLen = mb_strlen($text);
 
         $pos = mb_strpos(mb_strtolower($text), mb_strtolower($phrase));
         if ($pos === false) {
             return mb_substr($text, 0, $radius) . $ellipsis;
         }
 
         $startPos = $pos - $radius;
         if ($startPos <= 0) {
             $startPos = 0;
             $prepend = '';
         }
 
         $endPos = $pos + $phraseLen + $radius;
         if ($endPos >= $textLen) {
             $endPos = $textLen;
             $append = '';
         }
 
         $excerpt = mb_substr($text, $startPos, $endPos - $startPos);
         $excerpt = $prepend . $excerpt . $append;
 
         return $excerpt;
     }
 
 /**
691:  * Creates a comma separated list where the last two items are joined with 'and', forming natural language.
692:  *
693:  * @param array $list The list to be joined.
694:  * @param string $and The word used to join the last and second last items together with. Defaults to 'and'.
695:  * @param string $separator The separator used to join all the other items together. Defaults to ', '.
696:  * @return string The glued together string.
697:  * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/text.html#TextHelper::toList
698:  */
     public static function toList($list, $and = null, $separator = ', ') {
         if ($and === null) {
             $and = __d('cake', 'and');
         }
         if (count($list) > 1) {
             return implode($separator, array_slice($list, null, -1)) . ' ' . $and . ' ' . array_pop($list);
         }
 
         return array_pop($list);
     }
 }