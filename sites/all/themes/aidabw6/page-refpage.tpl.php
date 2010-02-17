<?php
// $Id: page.tpl.php,v 1.1 $
// Created for AIDA by Buttonwillow Six
// www.buttonwillowsix.com 
// based on Beginning  by gazwal.com
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php print $language->language ?>" xml:lang="<?php print $language->language ?>">
  <head>  
    <title>
      <?php print $head_title; ?>
    </title>  
    <?php print $head; ?>  
    <?php print $styles; ?>
    <!--[if lte IE 6]>
  <style type="text/css" media="all">
    @import "<?php echo $base_path . path_to_theme() ?>/ie6.css";
  </style>
  <![endif]-->
  <!--[if IE 7]>
  <style type="text/css" media="all">
    @import "<?php echo $base_path . path_to_theme() ?>/ie7.css";
  </style>
  <![endif]--> 
    <?php print $scripts; ?>	
</head>
 
  <body class="<?php print $body_classes; ?>">  
   
<!--PROJECT PAGE --> 
    <?php if (!empty($header)): ?> 	
    <div id="header-region">
      <?php print $header; ?>
    </div>
    <?php endif; ?>	
    
    <div id="container">		 		
      
      <div id="header">			
        
        <div id="top-bar">			           
        <!--  BW6 THIS IS COMMENTED OUT BECAUSE SECONDARY LINKS ARE SHOWING UP EVEN WHEN NOT SET IN THEME
 		<?php if (isset($secondary_links)) : ?>				           
          <div class="region-content">                     
            <?php print theme('links', $secondary_links, array('class' => 'links secondary-links')) ?>				           
          </div>                 
          <?php endif; ?>		-->	         
        </div>			 				
        
        <div class="region-content">
        <?php
				if ($logo || $site_title) {
        	print '<h1><a href="'. check_url($base_path) .'" title="'. $site_title .'">';
        	if ($logo) {
          	print '<img src="'. check_url($logo) .'" alt="'. $site_title .'" id="logo" title="Home" />';
        	}
        	print ($logo ? '' : $site_title_html) .'</a></h1>';
      	}
        ?>			 		
          
          <div id="top-primary">
          <?php if (isset($primary_links)) : ?>         	
          <?php print theme('links', $primary_links, array('class' => 'links primary-links')) ?>       	
          <?php endif; ?>
          </div>
          
        </div>		
      
      </div> 
      <!-- /header -->		 		
      
      
      <div id="center">			
        
        <div id="featured">				
          <?php if ($mission || $featured): ?>				
          <div class="region-content">				
				  	  <?php if ($mission): ?>
								<div id="mission"><?php print $mission; ?></div>
							<?php endif; ?>				
              <?php if ($featured): print $featured; endif; ?>				
          </div>				
          <?php endif; ?>			
        </div>			 			
        
       <?php if ($breadcrumb): ?>
       <div id="breadcrumb-wrapper">
          <div class="region-content">
            <?php print $breadcrumb; ?>
          </div>
       </div>
       <?php endif; ?>
        
        <div id="mainOut">				
          <div id="mainIn">					
            
            		
                <div id="content">						
                  <div id="squeeze">						
                    <?php if ($title) { ?><h1 class="pagetitle"><?php print $title ?></h1><?php } ?>
                    <?php if ($tabs) { ?><div class="tabs"><?php print $tabs ?></div><?php } ?>
                    <?php if ($show_messages && $messages): print $messages; endif; ?>
                    <?php if ($help): print $help; endif; ?>
                    
                    <?php print $content; ?>

<!--BW6 NEWS AND UDPATES, A DIFFERENT WAY -->
<!--Commented out until I can figure out how to make it refer back to parent project instead of refpage -->
				<!--<DIV Class="newslinks">-->
					<?php
					    //global $language;
					    //$lc = $language->language;
						//$vid = 3;
						//$terms = taxonomy_node_get_terms_by_vocabulary($node, $vid);

						//if ($lc=="en") print "<P><H2>News and Updates</H2></P>";
						//if ($lc=="es") print "<P><H2>Notas de prensa y boletines mensuales</H2></P>";
						
						//NEED NEW CODE TO GO HERE. Maybe pull Project Node ID out of Nodereference field?

						 
						 //}

					?>
					<!--</DIV>-->
<!--BW6 END ATTEMPT TO DO NEWS AND UDPATES A DIFFERENT WAY -->


                    
                    <div id="content-bottom">				
                    <?php if ($content_bottom): ?>
                      <div class="region-content">
                        <?php print $content_bottom; ?>
                      </div>
                    <?php endif; ?>		
                    </div>		 		

	                  <?php print $feed_icons; ?>
	                  
                  </div>					
                </div> 
                <!-- /content -->					
                
                
                <?php if ($left): ?>	        
                <div id="sidebar-left" class="sidebar">	         	
                  <?php if ($search_box): ?><div class="block block-theme" id="searchbox"><?php print $search_box ?></div><?php endif; ?>
                  <?php print $left ?>	       	
                </div> 
                <!-- /sidebar-left -->	      	
                <?php endif; ?>	 					
                
                <?php if ($right): ?> 	        
                <div id="sidebar-right" class="sidebar">	          
                  <?php print $right ?>	        
                </div> 
                <!-- /sidebar-right -->	      	
                <?php endif; ?>
                
                <span class="clear">&nbsp;</span><!-- important !!! -->
  
            </div><!-- /mainIn -->	
          </div><!-- /mainOut -->	
          
        </div> <!-- /center -->	
        		 			
  	 		
      <div id="footer">
         		
            <div class="region-content">					
              
              <?php if ($footer_top): ?> 	        
                <div id="footer-top">	          
                  <?php print $footer_top ?>	        
                </div>      	
              <?php endif; ?>	 	
              
              <?php if ($footer_left): ?> 	        
                <div id="footer-left" class="footer-block">	          
                  <?php print $footer_left ?>	        
                </div>      	
              <?php endif; ?>	 	
              
              <?php if ($footer_middle): ?> 	        
                <div id="footer-middle" class="footer-block">	          
                  <?php print $footer_middle ?>	        
                </div>      	
              <?php endif; ?>
              
              <?php if ($footer_right): ?> 	        
                <div id="footer-right" class="footer-block">	          
                  <?php print $footer_right ?>	        
                </div>      	
              <?php endif; ?>
              
              <span class="clear">&nbsp;</span><!-- important !!! -->
               			
            </div>
              
            <div id="bottom-primary" class="clear">
              <?php if (isset($primary_links)) : ?>
                <div class="region-content">    	
                <?php print theme('links', $primary_links, array('class' => 'links primary-links')) ?>    	
                </div>
              <?php endif; ?>			
            </div>
            
            <div class="region-content">
            <?php if (isset($footer_message)) { print '<div id="footer-message"><p>'.$footer_message.'</p></div>'; } ?>
            </div>
            
      </div> 
      <!-- /footer -->	
    
    </div> 
    <!-- /container -->	
    <?php print $closure ?>
  </body>
</html>