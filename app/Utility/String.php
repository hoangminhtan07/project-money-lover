<?php
  2: /**
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
 18: 
 19: /**
 20:  * String handling methods.
 21:  *
 22:  * @package       Cake.Utility
 23:  */
 24: class String {
 25: 
 26: /**
 27:  * Generate a random UUID
 28:  *
 29:  * @see http://www.ietf.org/rfc/rfc4122.txt
 30:  * @return RFC 4122 UUID
 31:  */
 32:     public static function uuid() {
 33:         $node = env('SERVER_ADDR');
 34: 
 35:         if (strpos($node, ':') !== false) {
 36:             if (substr_count($node, '::')) {
 37:                 $node = str_replace(
 38:                     '::', str_repeat(':0000', 8 - substr_count($node, ':')) . ':', $node
 39:                 );
 40:             }
 41:             $node = explode(':', $node);
 42:             $ipSix = '';
 43: 
 44:             foreach ($node as $id) {
 45:                 $ipSix .= str_pad(base_convert($id, 16, 2), 16, 0, STR_PAD_LEFT);
 46:             }
 47:             $node = base_convert($ipSix, 2, 10);
 48: 
 49:             if (strlen($node) < 38) {
 50:                 $node = null;
 51:             } else {
 52:                 $node = crc32($node);
 53:             }
 54:         } elseif (empty($node)) {
 55:             $host = env('HOSTNAME');
 56: 
 57:             if (empty($host)) {
 58:                 $host = env('HOST');
 59:             }
 60: 
 61:             if (!empty($host)) {
 62:                 $ip = gethostbyname($host);
 63: 
 64:                 if ($ip === $host) {
 65:                     $node = crc32($host);
 66:                 } else {
 67:                     $node = ip2long($ip);
 68:                 }
 69:             }
 70:         } elseif ($node !== '127.0.0.1') {
 71:             $node = ip2long($node);
 72:         } else {
 73:             $node = null;
 74:         }
 75: 
 76:         if (empty($node)) {
 77:             $node = crc32(Configure::read('Security.salt'));
 78:         }
 79: 
 80:         if (function_exists('hphp_get_thread_id')) {
 81:             $pid = hphp_get_thread_id();
 82:         } elseif (function_exists('zend_thread_id')) {
 83:             $pid = zend_thread_id();
 84:         } else {
 85:             $pid = getmypid();
 86:         }
 87: 
 88:         if (!$pid || $pid > 65535) {
 89:             $pid = mt_rand(0, 0xfff) | 0x4000;
 90:         }
 91: 
 92:         list($timeMid, $timeLow) = explode(' ', microtime());
 93:         return sprintf(
 94:             "%08x-%04x-%04x-%02x%02x-%04x%08x", (int)$timeLow, (int)substr($timeMid, 2) & 0xffff,
 95:             mt_rand(0, 0xfff) | 0x4000, mt_rand(0, 0x3f) | 0x80, mt_rand(0, 0xff), $pid, $node
 96:         );
 97:     }
 98: 
 99: /**
100:  * Tokenizes a string using $separator, ignoring any instance of $separator that appears between
101:  * $leftBound and $rightBound.
102:  *
103:  * @param string $data The data to tokenize.
104:  * @param string $separator The token to split the data on.
105:  * @param string $leftBound The left boundary to ignore separators in.
106:  * @param string $rightBound The right boundary to ignore separators in.
107:  * @return mixed Array of tokens in $data or original input if empty.
108:  */
109:     public static function tokenize($data, $separator = ',', $leftBound = '(', $rightBound = ')') {
110:         if (empty($data)) {
111:             return array();
112:         }
113: 
114:         $depth = 0;
115:         $offset = 0;
116:         $buffer = '';
117:         $results = array();
118:         $length = strlen($data);
119:         $open = false;
120: 
121:         while ($offset <= $length) {
122:             $tmpOffset = -1;
123:             $offsets = array(
124:                 strpos($data, $separator, $offset),
125:                 strpos($data, $leftBound, $offset),
126:                 strpos($data, $rightBound, $offset)
127:             );
128:             for ($i = 0; $i < 3; $i++) {
129:                 if ($offsets[$i] !== false && ($offsets[$i] < $tmpOffset || $tmpOffset == -1)) {
130:                     $tmpOffset = $offsets[$i];
131:                 }
132:             }
133:             if ($tmpOffset !== -1) {
134:                 $buffer .= substr($data, $offset, ($tmpOffset - $offset));
135:                 if (!$depth && $data{$tmpOffset} === $separator) {
136:                     $results[] = $buffer;
137:                     $buffer = '';
138:                 } else {
139:                     $buffer .= $data{$tmpOffset};
140:                 }
141:                 if ($leftBound !== $rightBound) {
142:                     if ($data{$tmpOffset} === $leftBound) {
143:                         $depth++;
144:                     }
145:                     if ($data{$tmpOffset} === $rightBound) {
146:                         $depth--;
147:                     }
148:                 } else {
149:                     if ($data{$tmpOffset} === $leftBound) {
150:                         if (!$open) {
151:                             $depth++;
152:                             $open = true;
153:                         } else {
154:                             $depth--;
155:                         }
156:                     }
157:                 }
158:                 $offset = ++$tmpOffset;
159:             } else {
160:                 $results[] = $buffer . substr($data, $offset);
161:                 $offset = $length + 1;
162:             }
163:         }
164:         if (empty($results) && !empty($buffer)) {
165:             $results[] = $buffer;
166:         }
167: 
168:         if (!empty($results)) {
169:             return array_map('trim', $results);
170:         }
171: 
172:         return array();
173:     }
174: 
175: /**
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
196:     public static function insert($str, $data, $options = array()) {
197:         $defaults = array(
198:             'before' => ':', 'after' => null, 'escape' => '\\', 'format' => null, 'clean' => false
199:         );
200:         $options += $defaults;
201:         $format = $options['format'];
202:         $data = (array)$data;
203:         if (empty($data)) {
204:             return ($options['clean']) ? String::cleanInsert($str, $options) : $str;
205:         }
206: 
207:         if (!isset($format)) {
208:             $format = sprintf(
209:                 '/(?<!%s)%s%%s%s/',
210:                 preg_quote($options['escape'], '/'),
211:                 str_replace('%', '%%', preg_quote($options['before'], '/')),
212:                 str_replace('%', '%%', preg_quote($options['after'], '/'))
213:             );
214:         }
215: 
216:         if (strpos($str, '?') !== false && is_numeric(key($data))) {
217:             $offset = 0;
218:             while (($pos = strpos($str, '?', $offset)) !== false) {
219:                 $val = array_shift($data);
220:                 $offset = $pos + strlen($val);
221:                 $str = substr_replace($str, $val, $pos, 1);
222:             }
223:             return ($options['clean']) ? String::cleanInsert($str, $options) : $str;
224:         }
225: 
226:         asort($data);
227: 
228:         $dataKeys = array_keys($data);
229:         $hashKeys = array_map('crc32', $dataKeys);
230:         $tempData = array_combine($dataKeys, $hashKeys);
231:         krsort($tempData);
232: 
233:         foreach ($tempData as $key => $hashVal) {
234:             $key = sprintf($format, preg_quote($key, '/'));
235:             $str = preg_replace($key, $hashVal, $str);
236:         }
237:         $dataReplacements = array_combine($hashKeys, array_values($data));
238:         foreach ($dataReplacements as $tmpHash => $tmpValue) {
239:             $tmpValue = (is_array($tmpValue)) ? '' : $tmpValue;
240:             $str = str_replace($tmpHash, $tmpValue, $str);
241:         }
242: 
243:         if (!isset($options['format']) && isset($options['before'])) {
244:             $str = str_replace($options['escape'] . $options['before'], $options['before'], $str);
245:         }
246:         return ($options['clean']) ? String::cleanInsert($str, $options) : $str;
247:     }
248: 
249: /**
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
260:     public static function cleanInsert($str, $options) {
261:         $clean = $options['clean'];
262:         if (!$clean) {
263:             return $str;
264:         }
265:         if ($clean === true) {
266:             $clean = array('method' => 'text');
267:         }
268:         if (!is_array($clean)) {
269:             $clean = array('method' => $options['clean']);
270:         }
271:         switch ($clean['method']) {
272:             case 'html':
273:                 $clean = array_merge(array(
274:                     'word' => '[\w,.]+',
275:                     'andText' => true,
276:                     'replacement' => '',
277:                 ), $clean);
278:                 $kleenex = sprintf(
279:                     '/[\s]*[a-z]+=(")(%s%s%s[\s]*)+\\1/i',
280:                     preg_quote($options['before'], '/'),
281:                     $clean['word'],
282:                     preg_quote($options['after'], '/')
283:                 );
284:                 $str = preg_replace($kleenex, $clean['replacement'], $str);
285:                 if ($clean['andText']) {
286:                     $options['clean'] = array('method' => 'text');
287:                     $str = String::cleanInsert($str, $options);
288:                 }
289:                 break;
290:             case 'text':
291:                 $clean = array_merge(array(
292:                     'word' => '[\w,.]+',
293:                     'gap' => '[\s]*(?:(?:and|or)[\s]*)?',
294:                     'replacement' => '',
295:                 ), $clean);
296: 
297:                 $kleenex = sprintf(
298:                     '/(%s%s%s%s|%s%s%s%s)/',
299:                     preg_quote($options['before'], '/'),
300:                     $clean['word'],
301:                     preg_quote($options['after'], '/'),
302:                     $clean['gap'],
303:                     $clean['gap'],
304:                     preg_quote($options['before'], '/'),
305:                     $clean['word'],
306:                     preg_quote($options['after'], '/')
307:                 );
308:                 $str = preg_replace($kleenex, $clean['replacement'], $str);
309:                 break;
310:         }
311:         return $str;
312:     }
313: 
314: /**
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
328:     public static function wrap($text, $options = array()) {
329:         if (is_numeric($options)) {
330:             $options = array('width' => $options);
331:         }
332:         $options += array('width' => 72, 'wordWrap' => true, 'indent' => null, 'indentAt' => 0);
333:         if ($options['wordWrap']) {
334:             $wrapped = self::wordWrap($text, $options['width'], "\n");
335:         } else {
336:             $wrapped = trim(chunk_split($text, $options['width'] - 1, "\n"));
337:         }
338:         if (!empty($options['indent'])) {
339:             $chunks = explode("\n", $wrapped);
340:             for ($i = $options['indentAt'], $len = count($chunks); $i < $len; $i++) {
341:                 $chunks[$i] = $options['indent'] . $chunks[$i];
342:             }
343:             $wrapped = implode("\n", $chunks);
344:         }
345:         return $wrapped;
346:     }
347: 
348: /**
349:  * Unicode and newline aware version of wordwrap.
350:  *
351:  * @param string $text The text to format.
352:  * @param int $width The width to wrap to. Defaults to 72.
353:  * @param string $break The line is broken using the optional break parameter. Defaults to '\n'.
354:  * @param bool $cut If the cut is set to true, the string is always wrapped at the specified width.
355:  * @return string Formatted text.
356:  */
357:     public static function wordWrap($text, $width = 72, $break = "\n", $cut = false) {
358:         $paragraphs = explode($break, $text);
359:         foreach ($paragraphs as &$paragraph) {
360:             $paragraph = String::_wordWrap($paragraph, $width, $break, $cut);
361:         }
362:         return implode($break, $paragraphs);
363:     }
364: 
365: /**
366:  * Unicode aware version of wordwrap as helper method.
367:  *
368:  * @param string $text The text to format.
369:  * @param int $width The width to wrap to. Defaults to 72.
370:  * @param string $break The line is broken using the optional break parameter. Defaults to '\n'.
371:  * @param bool $cut If the cut is set to true, the string is always wrapped at the specified width.
372:  * @return string Formatted text.
373:  */
374:     protected static function _wordWrap($text, $width = 72, $break = "\n", $cut = false) {
375:         if ($cut) {
376:             $parts = array();
377:             while (mb_strlen($text) > 0) {
378:                 $part = mb_substr($text, 0, $width);
379:                 $parts[] = trim($part);
380:                 $text = trim(mb_substr($text, mb_strlen($part)));
381:             }
382:             return implode($break, $parts);
383:         }
384: 
385:         $parts = array();
386:         while (mb_strlen($text) > 0) {
387:             if ($width >= mb_strlen($text)) {
388:                 $parts[] = trim($text);
389:                 break;
390:             }
391: 
392:             $part = mb_substr($text, 0, $width);
393:             $nextChar = mb_substr($text, $width, 1);
394:             if ($nextChar !== ' ') {
395:                 $breakAt = mb_strrpos($part, ' ');
396:                 if ($breakAt === false) {
397:                     $breakAt = mb_strpos($text, ' ', $width);
398:                 }
399:                 if ($breakAt === false) {
400:                     $parts[] = trim($text);
401:                     break;
402:                 }
403:                 $part = mb_substr($text, 0, $breakAt);
404:             }
405: 
406:             $part = trim($part);
407:             $parts[] = $part;
408:             $text = trim(mb_substr($text, mb_strlen($part)));
409:         }
410: 
411:         return implode($break, $parts);
412:     }
413: 
414: /**
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
430:     public static function highlight($text, $phrase, $options = array()) {
431:         if (empty($phrase)) {
432:             return $text;
433:         }
434: 
435:         $defaults = array(
436:             'format' => '<span class="highlight">\1</span>',
437:             'html' => false,
438:             'regex' => "|%s|iu"
439:         );
440:         $options += $defaults;
441:         extract($options);
442: 
443:         if (is_array($phrase)) {
444:             $replace = array();
445:             $with = array();
446: 
447:             foreach ($phrase as $key => $segment) {
448:                 $segment = '(' . preg_quote($segment, '|') . ')';
449:                 if ($html) {
450:                     $segment = "(?![^<]+>)$segment(?![^<]+>)";
451:                 }
452: 
453:                 $with[] = (is_array($format)) ? $format[$key] : $format;
454:                 $replace[] = sprintf($options['regex'], $segment);
455:             }
456: 
457:             return preg_replace($replace, $with, $text);
458:         }
459: 
460:         $phrase = '(' . preg_quote($phrase, '|') . ')';
461:         if ($html) {
462:             $phrase = "(?![^<]+>)$phrase(?![^<]+>)";
463:         }
464: 
465:         return preg_replace(sprintf($options['regex'], $phrase), $format, $text);
466:     }
467: 
468: /**
469:  * Strips given text of all links (<a href=....).
470:  *
471:  * @param string $text Text
472:  * @return string The text without links
473:  * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/text.html#TextHelper::stripLinks
474:  */
475:     public static function stripLinks($text) {
476:         return preg_replace('|<a\s+[^>]+>|im', '', preg_replace('|<\/a>|im', '', $text));
477:     }
478: 
479: /**
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
495:     public static function tail($text, $length = 100, $options = array()) {
496:         $defaults = array(
497:             'ellipsis' => '...', 'exact' => true
498:         );
499:         $options += $defaults;
500:         extract($options);
501: 
502:         if (!function_exists('mb_strlen')) {
503:             class_exists('Multibyte');
504:         }
505: 
506:         if (mb_strlen($text) <= $length) {
507:             return $text;
508:         }
509: 
510:         $truncate = mb_substr($text, mb_strlen($text) - $length + mb_strlen($ellipsis));
511:         if (!$exact) {
512:             $spacepos = mb_strpos($truncate, ' ');
513:             $truncate = $spacepos === false ? '' : trim(mb_substr($truncate, $spacepos));
514:         }
515: 
516:         return $ellipsis . $truncate;
517:     }
518: 
519: /**
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
537:     public static function truncate($text, $length = 100, $options = array()) {
538:         $defaults = array(
539:             'ellipsis' => '...', 'exact' => true, 'html' => false
540:         );
541:         if (isset($options['ending'])) {
542:             $defaults['ellipsis'] = $options['ending'];
543:         } elseif (!empty($options['html']) && Configure::read('App.encoding') === 'UTF-8') {
544:             $defaults['ellipsis'] = "\xe2\x80\xa6";
545:         }
546:         $options += $defaults;
547:         extract($options);
548: 
549:         if (!function_exists('mb_strlen')) {
550:             class_exists('Multibyte');
551:         }
552: 
553:         if ($html) {
554:             if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
555:                 return $text;
556:             }
557:             $totalLength = mb_strlen(strip_tags($ellipsis));
558:             $openTags = array();
559:             $truncate = '';
560: 
561:             preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
562:             foreach ($tags as $tag) {
563:                 if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
564:                     if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
565:                         array_unshift($openTags, $tag[2]);
566:                     } elseif (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
567:                         $pos = array_search($closeTag[1], $openTags);
568:                         if ($pos !== false) {
569:                             array_splice($openTags, $pos, 1);
570:                         }
571:                     }
572:                 }
573:                 $truncate .= $tag[1];
574: 
575:                 $contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
576:                 if ($contentLength + $totalLength > $length) {
577:                     $left = $length - $totalLength;
578:                     $entitiesLength = 0;
579:                     if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
580:                         foreach ($entities[0] as $entity) {
581:                             if ($entity[1] + 1 - $entitiesLength <= $left) {
582:                                 $left--;
583:                                 $entitiesLength += mb_strlen($entity[0]);
584:                             } else {
585:                                 break;
586:                             }
587:                         }
588:                     }
589: 
590:                     $truncate .= mb_substr($tag[3], 0, $left + $entitiesLength);
591:                     break;
592:                 } else {
593:                     $truncate .= $tag[3];
594:                     $totalLength += $contentLength;
595:                 }
596:                 if ($totalLength >= $length) {
597:                     break;
598:                 }
599:             }
600:         } else {
601:             if (mb_strlen($text) <= $length) {
602:                 return $text;
603:             }
604:             $truncate = mb_substr($text, 0, $length - mb_strlen($ellipsis));
605:         }
606:         if (!$exact) {
607:             $spacepos = mb_strrpos($truncate, ' ');
608:             if ($html) {
609:                 $truncateCheck = mb_substr($truncate, 0, $spacepos);
610:                 $lastOpenTag = mb_strrpos($truncateCheck, '<');
611:                 $lastCloseTag = mb_strrpos($truncateCheck, '>');
612:                 if ($lastOpenTag > $lastCloseTag) {
613:                     preg_match_all('/<[\w]+[^>]*>/s', $truncate, $lastTagMatches);
614:                     $lastTag = array_pop($lastTagMatches[0]);
615:                     $spacepos = mb_strrpos($truncate, $lastTag) + mb_strlen($lastTag);
616:                 }
617:                 $bits = mb_substr($truncate, $spacepos);
618:                 preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
619:                 if (!empty($droppedTags)) {
620:                     if (!empty($openTags)) {
621:                         foreach ($droppedTags as $closingTag) {
622:                             if (!in_array($closingTag[1], $openTags)) {
623:                                 array_unshift($openTags, $closingTag[1]);
624:                             }
625:                         }
626:                     } else {
627:                         foreach ($droppedTags as $closingTag) {
628:                             $openTags[] = $closingTag[1];
629:                         }
630:                     }
631:                 }
632:             }
633:             $truncate = mb_substr($truncate, 0, $spacepos);
634:         }
635:         $truncate .= $ellipsis;
636: 
637:         if ($html) {
638:             foreach ($openTags as $tag) {
639:                 $truncate .= '</' . $tag . '>';
640:             }
641:         }
642: 
643:         return $truncate;
644:     }
645: 
646: /**
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
657:     public static function excerpt($text, $phrase, $radius = 100, $ellipsis = '...') {
658:         if (empty($text) || empty($phrase)) {
659:             return self::truncate($text, $radius * 2, array('ellipsis' => $ellipsis));
660:         }
661: 
662:         $append = $prepend = $ellipsis;
663: 
664:         $phraseLen = mb_strlen($phrase);
665:         $textLen = mb_strlen($text);
666: 
667:         $pos = mb_strpos(mb_strtolower($text), mb_strtolower($phrase));
668:         if ($pos === false) {
669:             return mb_substr($text, 0, $radius) . $ellipsis;
670:         }
671: 
672:         $startPos = $pos - $radius;
673:         if ($startPos <= 0) {
674:             $startPos = 0;
675:             $prepend = '';
676:         }
677: 
678:         $endPos = $pos + $phraseLen + $radius;
679:         if ($endPos >= $textLen) {
680:             $endPos = $textLen;
681:             $append = '';
682:         }
683: 
684:         $excerpt = mb_substr($text, $startPos, $endPos - $startPos);
685:         $excerpt = $prepend . $excerpt . $append;
686: 
687:         return $excerpt;
688:     }
689: 
690: /**
691:  * Creates a comma separated list where the last two items are joined with 'and', forming natural language.
692:  *
693:  * @param array $list The list to be joined.
694:  * @param string $and The word used to join the last and second last items together with. Defaults to 'and'.
695:  * @param string $separator The separator used to join all the other items together. Defaults to ', '.
696:  * @return string The glued together string.
697:  * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/text.html#TextHelper::toList
698:  */
699:     public static function toList($list, $and = null, $separator = ', ') {
700:         if ($and === null) {
701:             $and = __d('cake', 'and');
702:         }
703:         if (count($list) > 1) {
704:             return implode($separator, array_slice($list, null, -1)) . ' ' . $and . ' ' . array_pop($list);
705:         }
706: 
707:         return array_pop($list);
708:     }
709: }