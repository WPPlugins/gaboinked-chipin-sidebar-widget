<?php
/*
Plugin Name: Gaboinked! Chipin Sidebar Widget
Plugin URI: http://gaboink.net/2008/04/chipin-wordpress-widget/
Description: The Gaboinked! Chipin Sidebar Widget displays a configurable Chipin Widget on your Wordpress or WordPress MU Blog.  <a href="http://chipin.com">Chipins</a> allow you to organize group payments and fundraisers in a quick, easy, and secure way.
Author: the Gaboink! network: George Jones
Version: 1.1
Author URI: http://gaboink.net/
*/


/* 
   Copyright 2008, the Gaboink! network by George!.  All Rights Reserved.
   License: http://www.gnu.org/copyleft/gpl.html GNU/GPL See License.txt contained in distribution archive.
   
   The Gaboinked! Chipin Sidebar Widget is free software. This version may have been modified pursuant
   to the GNU General Public License, and as distributed it includes or is derivative of works licensed
   under the GNU General Public License or other free or open source software licenses.
*/


class GaboinkedChipinWidget {
  var $size = array(array('size'=>'120x60',
			     'width'=>120, 'height'=>60),
		       array('size'=>'125x125',
			     'width'=>125, 'height'=>125),
		       array('size'=>'120x240',
			     'width'=>120, 'height'=>240),
			   array('size'=>'160x250',
			     'width'=>160, 'height'=>250),
			   array('size'=>'220x220',
			     'width'=>220, 'height'=>220),
			   array('size'=>'234x60',
			     'width'=>234, 'height'=>60),
			   array('size'=>'250x250',
			     'width'=>250, 'height'=>250));
			     
  // static init callback
  function init() {
    // Check for the required plugin functions. This will prevent fatal
    // errors occurring when you deactivate the dynamic-sidebar plugin.
    if ( !function_exists('register_sidebar_widget') )
      return;

    $widget = new GaboinkedChipinWidget();

    // This registers our widget so it appears with the other available
    // widgets and can be dragged and dropped into any active sidebars.
    register_sidebar_widget('Chipin', array($widget,'display'));

     // This registers our optional widget control form.
    register_widget_control('Chipin', array($widget,'control'), 300, 200);
  }

  // This is the function outputs the Chipin Donation Widget
  function display($args) {
    // $args is an array of strings that help widgets to conform to
    // the active theme: before_widget, before_title, after_widget,
    // and after_title are the array keys. Default tags: li and h2.
    extract($args);

    $options = get_option('widget_chipin');

    $title = $options['title'];
    $widgetID = $options['widgetID'];
    $eventTitle = $options['eventTitle'];
    $eventDesc = $options['eventDesc'];
    $color = $options['color'];
    $size = $options['size'];
    
    switch($options['size']) {
    case '125x125':
      $width = '125';
      $height = '125';
      break;
    case '120x240':
      $width = '120';
      $height = '240';
      break;
    case '160x250':
      $width = '160';
      $height = '250';
      break;
    case '220x220':
      $width = '220';
      $height = '220';
      break;
    case '234x60':
      $width = '234';
      $height = '60';
      break;
    case '250x250':
      $width = '250';
      $height = '250';
      default;
    }
        
    switch($options['color']) {
    case 'blue':
      $color = 'blue';
      break;
    case 'brown':
      $color = 'brown';
      break;
    case 'gray':
      $color = 'gray';
      break;
    case 'green':
    default:
      $color = 'green';
    case 'red':
      $color = 'red';
      break;
    }
    
    // These lines generate our SideBar Output.
    echo $before_widget;
    if ($title)
      echo $before_title . $title . $after_title;
      echo '<center><embed src="http://widget.chipin.com/widget/id/'.$widgetID.' "flashVars="event_title='.$eventTitle.'&event_desc='.$eventDesc.'&color_scheme='.$color.'" type="application/x-shockwave-flash" allowScriptAccess="always" wmode="transparent" width="'.$width.'" height="'.$height.'"></embed></center><br /><br />';
      
      /*  Debug
      echo $widgetID.'<br />'.$eventTitle.'<br />'.$eventDesc.'<br />'.$width.'<br />'.$height.'<br />'.$color;
      */   
      echo $after_widget;
  }

  // This is the function that outputs the control form
  function control() {
    // Get our options and see if we're handling a form submission.
    $options = get_option('widget_chipin');
    if ( !is_array($options) )
      $options = array('title'=>'',
		       'widgetID' => '',
		       'eventTitle' => '',
		       'eventDesc' => '',
		       'size' => '125x125',
		       'color' => 'green');

    if ( $_POST['chipin-submit'] ) {
      
      // Remember to sanitize and format use input appropriately.
      $options['title'] = strip_tags(stripslashes($_POST['chipin-title']));
      $options['widgetID'] = $_POST['chipin-widgetID'];
      $options['eventTitle'] = strip_tags(stripslashes($_POST['chipin-eventTitle']));
      $options['eventDesc'] = $_POST['chipin-eventDesc'];
      $options['color'] = $_POST['chipin-color'];
      $options['size'] = $_POST['chipin-size'];
      update_option('widget_chipin', $options);
    }

    // Be sure you format your options to be valid HTML attributes.
    $title = htmlspecialchars($options['title'], ENT_QUOTES);
    $widgetID = htmlspecialchars($options['widgetID'], ENT_QUOTES);
    $eventTitle = htmlspecialchars($options['eventTitle'], ENT_QUOTES);
    $eventDesc = htmlspecialchars($options['eventDesc'], ENT_QUOTES);
    $size = $options['size'];
    $color = $options['color'];

    // Here is our little form segment. Notice that we don't need a complete form. This will be embedded into the existing form.
    echo '<p style="text-align:right"><label for="chipin-title">' . __('Title: ') . ' <input style="width: 200px" id="chipin-title" name="chipin-title" type="text" value="'.$title.'" /></label></p>';
    echo '<p style="text-align:right"><label for="chipin-widgetID">' . __('widgetID: ') . ' <input style="width: 200px" id="chipin-widgetID" name="chipin-widgetID" type="text" value="'.$widgetID.'" /></label></p>';
	echo '<p style="text-align:right"><label for="chipin-eventTitle">' . __('Chipin Title: ') . ' <input style="width: 200px" id="chipin-eventTitle" name="chipin-eventTitle" type="text" value="'.$eventTitle.'" /></label></p>';
    echo '<p style="text-align:left"><label for="chipin-eventDesc">' . __('Chipin Event Info: ') . ' <textarea id="chipin-eventDesc" name="chipin-eventDesc" cols="31" rows="3">'.$eventDesc.'</textarea></label></p>';
    
    //echo '<p style="text-align:left"><label for="chipin-eventDesc">' . __('Chipin Event Info: ') . ' <input style="width: 250px; height:75px" id="chipin-eventDesc" name="chipin-eventDesc" type="text" value="'.$eventDesc.'" /></label></p>';
       
    echo '<p style="text-align:right"><label for="chipin-color">' . __('Widget Color: ') . ' <select style="width: 200px" id="chipin-color" name="chipin-color">';
    echo '<option value="blue" '.($color=='blue'?'selected="selected"':'').'>Blue</option>';
    echo '<option value="brown" '.($color=='brown'?'selected="selected"':'').'>Brown</option>';
    echo '<option value="gray" '.($color=='gray'?'selected="selected"':'').'>Gray</option>';
    echo '<option value="green" '.($color=='green'?'selected="selected"':'').'>Green</option>';
    echo '<option value="red" '.($color=='red'?'selected="selected"':'').'>Red</option>';
    echo '</select></label></p>';
    
    echo '<p style="text-align:right"><label for="chipin-size">' . __('Widget Size: ') . ' <select style="width: 200px" id="chipin-size" name="chipin-size">';
    echo '<option value="125x125" '.($size=='125x125'?'selected="selected"':'').'>125x125</option>';
    echo '<option value="120x240" '.($size=='120x240'?'selected="selected"':'').'>120x240</option>';
    echo '<option value="160x250" '.($size=='160x250'?'selected="selected"':'').'>160x250</option>';
    echo '<option value="220x220" '.($size=='220x220'?'selected="selected"':'').'>220x220</option>';
    echo '<option value="234x60" '.($size=='234x60'?'selected="selected"':'').'>234x60</option>';
    echo '<option value="250x250" '.($size=='250x250'?'selected="selected"':'').'>250x250</option>';
    echo '</select></label></p>';
    
    // Please do not remove this, it is not viewable by your readers.
    echo '<p><strong>Support this plugin by "Chiping In" Below!</strong>';
    echo '<p><center><embed src="http://widget.chipin.com/widget/id/7c0d74df4aa9381f" flashVars="event_title=Like%20this%20widget%3F%20Please%20donate&event_desc=If%20you%20use%20and%20are%20a%20fan%20of%20the%20Gaboinked%21%20Chipin%20Widget%20for%20Wordpress%2C%20please%20help%20with%20a%20contribution.&color_scheme=blue" type="application/x-shockwave-flash" allowScriptAccess="always" wmode="transparent" width="234" height="60"></embed></center></p><br />';
	echo '<p>A little gas money is appreciated, and please share how you are using this widget by leaving a <a href="http://gaboink.net/2008/04/chipin-wordpress-widget/#comments">comment here</a>.</p><br />';
    
    echo '<input type="hidden" id="chipin-submit" name="chipin-submit" value="1" />';
  }
}

// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', array('GaboinkedChipinWidget','init'));
?>
