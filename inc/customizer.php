<?php

/*
* key value to use if an <optgroup> 
* isn't wanted in the <select> list
*/

$dont_show="dont_show";

/*
* here we only specialize render_content
* to implement 'fontselect' which does 
* a little more than 'select' in WP_Customize_Control.
* primarily, gives a <optgroup>
* if the key in the outer array assigned to
* $this->choices is $dont_show,
* don't generate an <optgroup>
*/

if (class_exists('WP_Customize_Control')) {
   class WP_Customize_Font_Control extends WP_Customize_Control
   {
      private function do_content(){
         global $dont_show;
         $input_id         = '_customize-input-' . $this->id;
         $description_id   = '_customize-description-' . $this->id;
         $describedby_attr = (!empty($this->description)) ? ' aria-describedby="' . esc_attr($description_id) . '" ' : '';
         if (empty($this->choices)) {
            return;
         }
         if (!empty($this->label)) { ?>
         <label for="<?php echo esc_attr($input_id); ?>" class="customize-control-title"><?php echo esc_html($this->label); ?></label>
         <?php 
         } 
         if (!empty($this->description)) { ?>
         <span id="<?php echo esc_attr($description_id); ?>" class="description customize-control-description"><?php echo $this->description; ?></span>
         <?php } ?>
         <select id="<?php echo esc_attr($input_id); ?>" <?php echo $describedby_attr; ?> <?php $this->link(); ?>>
         <?php
            foreach ($this->choices as $group_label => $font_arr){ 
               if ($group_label !== $dont_show) {?> 
               <optgroup label="<?php echo $group_label; ?>">
               <?php }
               foreach ($font_arr as $value => $label) {
                  echo '<option value="' . esc_attr($value) . '"' . selected($this->value(), $value, false) . '>' . $label . '</option>';
               }
            }
            if ($group_label !== $dont_show) { ?></optgroup> <?php } ?>
         </select>
         <?php
      }
      public function render_content()
      {
         switch ($this->type) {
            case 'fontselect':
               $this->do_content();
               break;
            default:
               parent::render_content();
               break;
         }
      }
   }  
}

/*
* LS custom
* do data-driven configuration of the font
* drop down <optgroup>'s and <options>.
* the outer array is the optgroup with the label
* as the key. The value is the array with the 
* font-family, font-style, etc. CSS values as keys
* and the labels for the <otion> tags. See
* do_content()
* if the key in the outer array is $dont_show,
* don't generate an <optgroup>
* default system font list found at:
* https://devhints.io/css-system-font-stack
*/
$global_fonts_arr = array(
   __('System Fonts') => array(
   '-apple-system, BlinkMacSystemFont,"Segoe UI", "Roboto", "Oxygen","Ubuntu", "Cantarell", "Fira Sans","Droid Sans", "Helvetica Neue", sans-serif' => __('Default System Font'),
   'cursive, sans-serif'   => __('Cursive'),
   'Courier, Lucida Console, monospace' => __('Courier'),
   'Lucida Console, Courier, monospace' => __('Lucida Console'),
   'Monaco, Consolas, "Andale Mono", "DejaVu Sans Mono"' => __('Monaco'),
   '"Times New Roman", Times, Georgia, serif' => __('Times New Roman'),
   'Georgia, "Times New Roman", Times, serif' => __('Georgia'),
   'Verdana, Arial, Helvetica, sans-serif' => __('Verdana'),
   'Arial, Helvetica, Verdana sans-serif' => __('Arial'),
   'Helvetica, Verdana, Arial, sans-serif' => __('Helvetica'),
   'fantasy, sans-serif' => __('Fantasy'),
   ),
   __('Google Fonts') => array()
);
$menu_fonts_arr = array(
   __('Inherit From Body') => array(
      'inherit' => __('Inherit')
   ),
   __('System Fonts') => array(
   '-apple-system, BlinkMacSystemFont,"Segoe UI", "Roboto", "Oxygen","Ubuntu", "Cantarell", "Fira Sans","Droid Sans", "Helvetica Neue", sans-serif' => __('Default System Font'),
   'cursive, sans-serif'   => __('Cursive'),
   'Courier, Lucida Console, monospace' => __('Courier'),
   'Lucida Console, Courier, monospace' => __('Lucida Console'),
   'Monaco, Consolas, "Andale Mono", "DejaVu Sans Mono"' => __('Monaco'),
   '"Times New Roman", Times, Georgia, serif' => __('Times New Roman'),
   'Georgia, "Times New Roman", Times, serif' => __('Georgia'),
   'Verdana, Arial, Helvetica, sans-serif' => __('Verdana'),
   'Arial, Helvetica, Verdana sans-serif' => __('Arial'),
   'Helvetica, Verdana, Arial, sans-serif' => __('Helvetica'),
   'fantasy, sans-serif' => __('Fantasy'),
   ),
   __('Google Fonts') => array()
);

$global_style_arr = array(
   __($dont_show) => array(
   'normal' => __('Normal'),
   'italic' => __('Italic'),
   'oblique' => __('Oblique')
   )
);

$menu_style_arr = array(
   __($dont_show) => array(
   'inherit' => __('Inherit'),
   'normal' => __('Normal'),
   'italic' => __('Italic'),
   )
);

function register($wp_customize)
{
   global $global_fonts_arr;
   global $menu_fonts_arr;
   global $global_style_arr;
   global $menu_style_arr;
   //1. Define a new section (if desired) to the Theme Customizer
   
   $wp_customize->add_section(
      'wileecoder_font_options',
      array(
         'title'       => __('Fonts', 'wileecoder'), //Visible title of section
         'priority'    => 35, //Determines what order this appears in
         'capability'  => 'edit_theme_options', //Capability needed to tweak
         'description' => __('Allows you to set main font for body.', 'wileecoder'), //Descriptive tooltip
      )
   );
   
   //2. Register new settings to the WP database...
   // setting for font family generally

   $wp_customize->add_setting(
      'global_font_family', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
      array(
         'default'    => 'sans-serif', //Default setting/value to save
         'type'       => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
         'capability' => 'edit_theme_options', //Optional. Special permissions for accessing this setting.
         'transport'  => 'refresh', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
      )
   );

   //3. Finally, we define the control itself (which links a setting to a section and renders the HTML controls)...
   // control for global, body font family

   $wp_customize->add_control(new WP_Customize_Font_Control( //Instantiate the color control class
      $wp_customize, //Pass the $wp_customize object (required)
      'wileecoder_global_font_family', //Set a unique ID for the control
      array(
         'label'      => __('Body Font Family', 'wileecoder'), //Admin-visible name of the control
         'settings'   => 'global_font_family', //Which setting to load and manipulate (serialized is okay)
         'priority'   => 10, //Determines the order this control appears in for the specified section
         'section'    => 'wileecoder_font_options', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
         'type'       => 'fontselect',
         'choices'    => $global_fonts_arr
      )
   ));

   //4. Register next settings to the WP database...
   // setting for font style generally

   $wp_customize->add_setting(
      'global_font_style', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
      array(
         'default'    => 'normal', //Default setting/value to save
         'type'       => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
         'capability' => 'edit_theme_options', //Optional. Special permissions for accessing this setting.
         'transport'  => 'refresh', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
      )
   );

   //5. Finally, we define the control itself (which links a setting to a section and renders the HTML controls)...
   // control for global font style

   $wp_customize->add_control(new WP_Customize_Font_Control( //Instantiate the color control class
      $wp_customize, //Pass the $wp_customize object (required)
      'wileecoder_global_font_style', //Set a unique ID for the control
      array(
         'label'      => __('Body Font Style', 'wileecoder'), //Admin-visible name of the control
         'settings'   => 'global_font_style', //Which setting to load and manipulate (serialized is okay)
         'priority'   => 15, //Determines the order this control appears in for the specified section
         'section'    => 'wileecoder_font_options', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
         'type'       => 'fontselect',
         'choices'    => $global_style_arr
      )
   ));
   //6. Register next settings to the WP database...
   // setting for menu font family generally

   $wp_customize->add_setting(
      'menu_font_family', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
      array(
         'default'    => 'normal', //Default setting/value to save
         'type'       => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
         'capability' => 'edit_theme_options', //Optional. Special permissions for accessing this setting.
         'transport'  => 'refresh', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
      )
   );

   //7. Finally, we define the control itself (which links a setting to a section and renders the HTML controls)...
   // control for menu font family

   $wp_customize->add_control(new WP_Customize_Font_Control( //Instantiate the color control class
      $wp_customize, //Pass the $wp_customize object (required)
      'wileecoder_menu_font_family', //Set a unique ID for the control
      array(
         'label'      => __('Menu Font Family', 'wileecoder'), //Admin-visible name of the control
         'settings'   => 'menu_font_family', //Which setting to load and manipulate (serialized is okay)
         'priority'   => 17, //Determines the order this control appears in for the specified section
         'section'    => 'wileecoder_font_options', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
         'type'       => 'fontselect',
         'choices'    => $menu_fonts_arr
      )
   ));

   //8. Register next settings to the WP database...
   // setting for font style generally

   $wp_customize->add_setting(
      'menu_font_style', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
      array(
         'default'    => 'normal', //Default setting/value to save
         'type'       => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
         'capability' => 'edit_theme_options', //Optional. Special permissions for accessing this setting.
         'transport'  => 'refresh', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
      )
   );

   //9. Finally, we define the control itself (which links a setting to a section and renders the HTML controls)...
   // control for menu font style

   $wp_customize->add_control(new WP_Customize_Font_Control( //Instantiate the color control class
      $wp_customize, //Pass the $wp_customize object (required)
      'wileecoder_menu_font_style', //Set a unique ID for the control
      array(
         'label'      => __('Menu Font Style', 'wileecoder'), //Admin-visible name of the control
         'settings'   => 'menu_font_style', //Which setting to load and manipulate (serialized is okay)
         'priority'   => 19, //Determines the order this control appears in for the specified section
         'section'    => 'wileecoder_font_options', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
         'type'       => 'fontselect',
         'choices'    => $menu_style_arr
      )
   ));
   
}

/**
 * This will output the custom WordPress settings to the live theme's WP head.
 * 
 * Used by hook: 'wp_head'
 * 
 * @see add_action('wp_head',$func)
 * @since wileecoder 1.0
 */

function header_output()
{
   ?>
   <!--Customizer CSS-->
   <style type="text/css">
      <?php 
      generate_css('body', 'font-family', 'global_font_family');
      generate_css('body', 'font-style', 'global_font_style');
      generate_css('.main-navigation', 'font-family','menu_font_family');
      generate_css('.main-navigation', 'font-style','menu_font_style');
      generate_css('.footer-navigation', 'font-family','menu_font_family');
      generate_css('.footer-navigation', 'font-style','menu_font_style');
      ?>
   </style>
   <!--/Customizer CSS-->
<?php
}


/**
 * This will generate a line of CSS for use in header output. If the setting
 * ($mod_name) has no defined value, the CSS will not be output.
 * 
 * @uses get_theme_mod()
 * @param string $selector CSS selector
 * @param string $style The name of the CSS *property* to modify
 * @param string $mod_name The name of the 'theme_mod' option to fetch
 * @param string $prefix Optional. Anything that needs to be output before the CSS property
 * @param string $postfix Optional. Anything that needs to be output after the CSS property
 * @param bool $echo Optional. Whether to print directly to the page (default: true).
 * @return string Returns a single line of CSS with selectors and a property.
 * @since wileecoder 1.0
 */

function generate_css($selector, $style, $mod_name, $prefix = '', $postfix = '', $echo = true)
{
   $return = '';
   $mod = get_theme_mod($mod_name);
   if (!empty($mod)) {
      $return = sprintf(
         '%s { %s:%s; }',
         $selector,
         $style,
         $prefix . $mod . $postfix
      );
      if ($echo) {
         echo $return;
      }
   }
   return $return;
}

// Setup the Theme Customizer settings and controls...
add_action('customize_register', 'register');

// Output custom CSS to live site
add_action('wp_head', 'header_output');

?>