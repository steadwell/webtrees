<?php
/**
 * Lightbox Album module for phpGedView
 *
 * Display media Items using Lightbox
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2002 to 2009  PHPGedView Development Team
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package webtrees
 * @subpackage Module
 * @version $Id$
 * @author Brian Holland
 */

if (!defined('WT_WEBTREES')) {
 header('HTTP/1.0 403 Forbidden');
 exit;
}

global $LB_AL_HEAD_LINKS,$LB_AL_THUMB_LINKS,$LB_ML_THUMB_LINKS,$LB_SS_SPEED;
global $LB_MUSIC_FILE,$LB_TRANSITION,$LB_URL_WIDTH,$LB_URL_HEIGHT,$LB_TT_BALLOON,$GEDCOM;

$LB_AL_HEAD_LINKS=get_module_setting('lightbox', 'LB_AL_HEAD_LINKS', 'both');   // Album Tab Page Header Links.
          // Set to 'icon' to view icon links.
          // Set to 'text' to view text links
          // Set to 'both' to view both.

$LB_TT_BALLOON=get_module_setting('lightbox', 'LB_TT_BALLOON', 'true');    // Album Tab Page - Above Thumbnail Links.
          // Set to 'true' to view Tooltip Balloon.
          // Set to 'false' to view Tooltip Normal.

$LB_AL_THUMB_LINKS=get_module_setting('lightbox', 'LB_AL_THUMB_LINKS', 'text');   // Album Tab Page - Below Thumbnail Links.
          // Set to 'icon' to view icon links.
          // Set to 'text' to view text links. [Default]

$LB_SS_SPEED=get_module_setting('lightbox', 'LB_SS_SPEED', '6');     // SlideShow speed in seconds.  [Min 2  max 25]

$LB_MUSIC_FILE=get_module_setting('lightbox', 'LB_MUSIC_FILE', 'modules/lightbox/music/music.mp3');  // The music file. [mp3 only]

$LB_TRANSITION=get_module_setting('lightbox', 'LB_TRANSITION', 'warp');   // Next or Prvious Image Transition effect
          // Set to 'none'  No transtion effect.
          // Set to 'normal'  Normal transtion effect.
          // Set to 'double'  Fast transition effect.
          // Set to 'warp'  Stretch transtition effect. [Default]

$LB_URL_WIDTH =get_module_setting('lightbox', 'LB_URL_WIDTH',  '1000'); //  URL Window width in pixels
$LB_URL_HEIGHT=get_module_setting('lightbox', 'LB_URL_HEIGHT', '600'); //  URL Window height in pixels

$LB_ML_THUMB_LINKS = get_module_setting('lightbox', 'LB_ML_THUMB_LINKS', 'text');   // MultiMedia List Page Thumbnail Links
          // Set to 'icon' to view icon links.
          // Set to 'text' to view text links. [Default]
          // Set to 'both' to view both.
          // Set to 'none' to view neither.
