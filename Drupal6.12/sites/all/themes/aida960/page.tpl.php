
<?php
// $Id: page.tpl.php,v 1.5 2009/05/14 13:56:51 robby Exp $
//Site by Buttonwillow Six http://www.buttonwillowsix.com
//  based on beginning Web 2.0 drupal 6.x theme, created by robin / biboo.net / //gazwal.com
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
    
    <?php if ($header): print '<div id="header-region-wrapper"><div id="header-region">'.$header.'</div></div>'; endif; ?>
    
    <div id="container">		 		
      
      <div id="header">			
        
        <div id="top-bar">			           
          <?php if (isset($secondary_links)) : ?>				           
          <div class="region-content">                     
            <?php print theme('links', $secondary_links, array('class' => 'links secondary-links')) ?>				           
          </div>                 
          <?php endif; ?>			         
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
           <div id="top-primary-left">
           <div id="top-primary-right">
           <?php if (isset($primary_links)) : ?>         	
           <?php print theme('links', $primary_links, array('class' => 'links primary-links')) ?>       	
           <?php endif; ?>
           </div>
           </div>
          </div>
          
        </div>		
      
      </div> 
      <!-- /header -->		 		
      
      
      <div id="center">			
        
        <div id="featured">		
          <?php if ($mission || $featured): ?>				
          <div class="region-content">				
            <?php if ($mission): print '<div id="mission"><p>'. $mission .'</p></div>'; endif; ?>				
            <?php if ($featured): print $featured; endif; ?>				
          </div>				
          <?php endif; ?>			
        </div>
			 			
	<div id="headerblock">
	<div class="region-content">

              <?php if ($headerblock_left): ?>
                <div id="headerblock-left" class="headerblock-block">
                  <?php print $headerblock_left ?>
                </div>
              <?php endif; ?>

              <?php if ($headerblock_middle): ?>
                <div id="headerblock-middle" class="headerblock-block">
                  <?php print $headerblock_middle?>
                </div>
              <?php endif; ?>

              <?php if ($headerblock_right): ?>
                <div id="headerblock-right" class="headerblock-block">
                  <?php print $headerblock_right ?>
                </div>
              <?php endif; ?>

              <span class="clear">&nbsp;</span><!-- important !!! -->

            </div>
        </div>

          <div id="breadcrumb-wrapper">
          <?php if ($breadcrumb): print '<div class="region-content">'.$breadcrumb.'</div>'; endif; ?>
          </div>
        
        <div id="mainOut">				
          <div id="mainIn">					
            
            <div class="top-corners">              
              <div class="bottom-corners">
            
                <div id="content">						
                  <div id="squeeze">						
                    <?php if ($title) { ?><h1 class="pagetitle"><?php print $title ?></h1><?php } ?>
                    <?php if ($tabs) { ?><div class="tabs"><?php print $tabs ?></div><?php } ?>
                    <?php if ($show_messages && $messages): print $messages; endif; ?>
                    <?php if ($help): print $help; endif; ?>
                    
                    <?php print $content; ?>
                    
                    <div id="content-bottom">				
                    <?php if ($content_bottom): print '<div class="region-content">'.$content_bottom.'</div>'; endif; ?>			
                    </div>	
	        
	                  <?php print '<div class="div-feed-icons">'. $feed_icons .'</div>'; ?>
	                  
                  </div>					
                </div> 
                <!-- /content -->					
                
                
                <?php if ($left): ?>	        
                <div id="sidebar-left" class="sidebar">
                  <?php print $left ?>	       	
                </div> 
                <!-- /sidebar-left -->	      	
                <?php endif; ?>	 					
                
                <?php if ($right): ?> 	        
                <div id="sidebar-right" class="sidebar">	          
                  <?php if ($search_box): ?><div class="block block-theme" id="searchbox"><?php print $search_box ?></div><?php endif; ?>
                  <?php print $right ?>	        
                </div> 
                <!-- /sidebar-right -->	      	
                <?php endif; ?>
                
                <span class="clear">&nbsp;</span><!-- important !!! -->
                
                </div><!-- /bottom-corners -->       
              </div><!-- /top-corners -->
        
            </div><!-- /mainIn -->	
          </div><!-- /mainOut -->	
          		 		

        </div> <!-- /center -->	
        		 			
  	 		
      <div id="footer">
      
         		<?php if ($footer_top): ?> 	        
                <div id="footer-top-wrapper">
                  <div id="footer-top">	          
                    <?php print $footer_top ?>	        
                  </div>
                </div>     	
            <?php endif; ?>	 	
            	
            <div class="region-content">					
              
              <?php if ($footer_left): ?> 	        
                <div id="footer-left" class="footer-block">	          
                  <?php print $footer_left ?>	        
                </div>      	
              <?php endif; ?>	 	
              
              <?php if ($footer_middle_left): ?> 	        
                <div id="footer-middle-left" class="footer-block">	          
                  <?php print $footer_middle_left ?>	        
                </div>      	
              <?php endif; ?>
              
              <?php if ($footer_middle_right): ?> 	        
                <div id="footer-middle-right" class="footer-block">	          
                  <?php print $footer_middle_right ?>	        
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
