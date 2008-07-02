<?php
/*
Plugin Name: jMaki Accordion
Description: Accordion to display links.
Author: Karthik Bala
Version: 0.1
Author URI: http://karthikbala.com
Plugin URI: http://wordpress.org/extend/plugins/jmaki-accordion/
*/
    
    function accordion() 
    {                      
        addWidget( array(
                          "name" => "jmaki.accordionMenu",
                          "value" =>"{menu : [
                           {label:  'Blogs',
                                menu: [
                                    { label : ' Greg Murray',
                                      href : 'http://weblogs.java.net/blog/gmurray71/'
                                    },
                                    { label : 'J Naveen',
                                      href : 'http://jaininaveen.blogspot.com/'
                                    },
                                    { label : 'Prashanth Ellina',
                                      href : 'http://blog.prashanthellina.com/'
                                    },
                                    { label : 'Carla Mott',
                                      href : 'http://weblogs.java.net/blog/carlavmott/'
                                    },
                                    { label : 'Arun Gupta',
                                      href : 'http://blogs.sun.com/arungupta/'
                                    },
                                     { label : 'Vikraman',
                                      href : 'http://vikraman.blogspot.com/'
                                    }    
                                    ]
                           },                          
                           {label: 'Sites',
                                menu: [
                                    { label : 'TollyZone',
                                      href : 'http://jmaki.karthikbala.com/tollyzone'
                                     },
                                    { label : 'Zenmocha',
                                      href : 'http://www.zenmocha.com'
                                    },
                                    { label : 'TechSpirit',
                                      href : 'http://www.karthikbala.com'
                                    },
                                    { label : 'Indian Capitals',
                                      href : 'http://indiancapitals.com'
                                    }
                                    ]
                           }
                                    ]
                       }"
            ) );
    }

    function widget_accordion($args) {
        extract($args);
        $options = get_option("widget_accordion");
        $title = htmlspecialchars(stripslashes($options['title']));
        if (!is_array( $options ))
        {
            $options = array(
      'title' => 'jmaki'
            ); 
        }      
        echo $before_widget.$before_title.$title.$after_title;
        echo '<ul>'."\n";
        //Our Widget Content
        accordion();
        echo '</ul>'."\n";
        echo $after_widget;
    }

    function accordion_control() 
    {
        $options = get_option("widget_accordion");  
        if (!is_array( $options ))
        {
            $options = array(
      'title' => 'jMaki'
            ); 
        }      
  
        if ($_POST['accordion-Submit']) 
        {
            $options['title'] = htmlspecialchars($_POST['accordion-WidgetTitle']);
            update_option("widget_accordion", $options);
        }
  
    ?>
<p>
<label for="accordion-WidgetTitle">Widget Title: </label>
<input type="text" id="accordion-WidgetTitle" name="accordion-WidgetTitle" value="<?php echo $options['title'];?>" />
<input type="hidden" id="accordion-Submit" name="accordion-Submit" value="1" />
</p>
<?php
}

function accordion_init()
{
    register_sidebar_widget(__('jMaki Accordion'), 'widget_accordion');    
    register_widget_control(   'jMaki Accordion', 'accordion_control', 300, 200 );	 
}
add_action("plugins_loaded", "accordion_init");


$resourceRoot = "/resources";
$cwd = getcwd();
$resourceDir = "$cwd/wp-content/plugins/jmaki-accordion/resources/";
//$resourceDir = "resources/";
$globalTheme = null;

//Karthik changing serverroot frm context to cwd
$domain = get_option('siteurl');
$contextRoot = "{$domain}/wp-content/plugins/jmaki-accordion";
$serverRoot = $contextRoot;
// write out the jmaki script and friends
echo "<script type='text/javascript' src='" .  $contextRoot . $resourceRoot ."/jmaki-min.js'></script>\n";
echo "<script type='text/javascript'>jmaki.webRoot='" . $contextRoot . "'; jmaki.resourcesRoot ='" . $resourceRoot . "'; jmaki.xhp='" . $contextRoot . "/XmlHttpProxy.php';</script>\n";
echo "<link type=\"text/css\" rel=\"stylesheet\" href='" .  $contextRoot . $resourceRoot . "/jmaki/resources/styles/themes/kame/theme.css'></link>\n";
function addWidget($props) {
    global $json, $contextRoot, $types, $pagelibs,
    $pagestyles, $resourceDir, $currentURI, $widgetPath, $uuid, $serverRoot, $globalTheme,
    $combineScripts, $combineStyles, $combineResourcesMaxAge, $combinedStyles, $combinedScripts, $useMinimizedJS;

    //echo "context root is {$contextRoot}";
    //by karthik
    //contextRoot and resourceDir global values are not reflecting here, hence they are setted again
    $domain = get_option('siteurl');
    $contextRoot = "{$domain}/wp-content/plugins/jmaki-accordion";
    $cwd = getcwd();
    $resourceDir = "$cwd/wp-content/plugins/jmaki-accordion/resources/";
    //$resourceRoot = "/resources";
    $name = null;
    $args = null;
    $service = null;
    $value = null;
    $id = null;
    $publish = null;
    $subscribe = null;
    $typeArgs = null;

    $isObject = false;
    if (gettype($props) == 'array') {
        if(array_key_exists ('name', $props)) $name = $props['name'];
        if(array_key_exists ('value', $props)) $value = $props['value'];
        if(array_key_exists ('service', $props)) $service = $props['service'];
        if(array_key_exists ('args', $props)) $args = $props['args'];
        if(array_key_exists ('id', $props)) $id = $props['id'];
        if(array_key_exists ('publish', $props)) $publish = $props['publish'];
        if(array_key_exists ('subscribe', $props)) $subscribe = $props['subscribe'];
    } else {
        $name = $props;
        // this is a way around function overloading
        if (func_num_args()  > 1 ){
            $service = func_get_arg(1);
        }
        if (func_num_args()  > 2) {
            $args = func_get_arg(2);
        }
        if (func_num_args()  > 3) {
            $value = func_get_arg(3);
        }
        if (func_num_args()  > 4) {
            $id = func_get_arg(4);
        }
        if (func_num_args()  > 5) {
            $publish = func_get_arg(5);
        }
        if (func_num_args()  > 6) {
            $subscribe = func_get_arg(6);
        }	        
    }

    if ($service != null) { 	
        // use the current directory if not starting with /
        if ($service[0] != "/") {
            $service = $currentURI . $service;
        } else {
            $service = $contextRoot . "/" . $service;
        }
    }

    $type = null;

    if ($id == null) {
        $uuid =  generateUUID($name);
    } else {
        $uuid = $id;
    }

    $widgetPath = preg_replace('/\./', '/', $name);
    $widgetDir = $contextRoot . '/resources/' . $widgetPath;

    if ($typeArgs == null) {
        //writeType($type, $name);
    } else {
        $typeArray = array();
        $typeArray = explode(",", $typeArgs);
        for($i = 0;$i < sizeof($typeArray); $i++) {
            //writeType($typeArray[$i], $name);
        }
    }

    // add the template
    $widgetCSS = $resourceDir . $widgetPath . '/component.css';
    $style = $widgetDir .  "/component.css"; 
    if (file_exists($widgetCSS) && !hasKey($pagestyles, $style)) {
        if ($combineStyles) {	         
            array_push($combinedStyles["styles"], $widgetCSS);
            array_push($combinedStyles["widgetDirs"], $widgetDir . "/");	         
            array_push($combinedStyles["widgetNames"], $name);
        } else {
            echo "<link type='text/css' rel='stylesheet' href='" . $style . "'></link>\n";
        }
        array_push($pagestyles,$style);
    } 
    // get the template component
    $template = null; 

    if  (file_exists($resourceDir . $widgetPath . '/component.htm')) {
        $template = file_get_contents($resourceDir . $widgetPath . '/component.htm');
    } else if (file_exists($resourceDir . $widgetPath . '/component.html')){
        $template = file_get_contents($resourceDir . $widgetPath . '/component.html');
    } else {
        echo "jMaki Error: Unable to locate template file";
    }
    $comp = "/component.js";

    // use the minimized script if in optimized mode
    if  ($useMinimizedJS && 
        file_exists($resourceDir . $widgetPath . '/component-min.js')) {
        $comp = "/component-min.js";
    }
    $lib = $widgetDir . $comp;
    // write out the script for the component.js

    if (!hasKey($pagelibs, $lib)){
        if ($combineScripts) {	         
            array_push($combinedScripts["libs"], $resourceDir . $widgetPath . $comp);
            array_push($combinedScripts["widgetNames"], $name);
        } else {
            echo "<script type='text/javascript' src='" . $lib . "'></script>\n";
        }
        $pagelibs = $lib;
    }

    // now replace what needs to be replaced
    $template = preg_replace('/\$\{uuid\}/',  $uuid, $template);
    if (($value) && !strstr($value, '@{') ) {
        $template = preg_replace('/\$\{value\}/',  $value, $template);
    } else if (($value) && strstr($value, '@{') ) {
        $template = preg_replace('/\$\{value\}/',  '', $template);
    }
    $template = preg_replace('/\$\{service\}/',  $service, $template);
    echo $template;
    echo "\n";
    // now serialize the arguments and pass them into the widget.
    echo "<script type='text/javascript'>jmaki.addWidget({" . 
              "uuid:" .  "\"" . $uuid . "\"," .
              "name:" .  "\"" . $name . "\",";
                      
    if ($service) {
        echo "service: '" . $service . "',";
    }
    if (($value) && strstr($value, '{') && !strstr($value, '@{') ) {
        echo "value: " . $value . ",";
    } else if ($value) {
        echo "value: \"" . $value . "\",";
    }
    if ($args) {
        echo "args: " . $args . ",";
    }
    if ($publish) {
        echo "publish: \"" . $publish . "\",";
    }
    if ($subscribe) {
        echo "subscribe: \"" . $subscribe . "\",";
    }
    echo "widgetDir:" .  "\"" . $widgetDir . "\"" .	"});</script>\n";         
}
function hasKey($tarray, $key) {
    for($i = 0;$i < sizeof($tarray); $i++) {
        if ($tarray[$i] == $key) {
            return true;
        }
    }
    return false;  
}
function generateUUID($name) {
    $counter = $_SESSION['uuid'] + 1;
    $_SESSION['uuid'] = $counter;
    return (preg_replace('/\./', '_', $name)   . '_' . $counter++);
}

?>

