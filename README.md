# PosterGen - News and Articles poster generator

Use for:
* Poster for your site or blog
* Poster for social network
* Poster for your promo materials &#9787;

# Example Images
![PosterGen](https://github.com/ange007/poster-gen/blob/master/icon.png) 

![PosterGen](https://github.com/ange007/poster-gen/blob/master/poster.png)

# Example Code
```php
<?php
    require_once '../src/Options.php';
    require_once '../src/Utils.php';
    require_once '../src/Draw.php';
    require_once '../src/PosterGen.php';
    
    // Generate poster
    $poster = ( new \PosterGen\PosterGen( [ ] ) )
        ->setSize( 1280, 720 )
        ->setBackgroundImage( __DIR__ . "/backgrounds/1.jpg" )
        ->setHorizontalAlignment( 'center' )
        ->setVerticalAlignment( 'center' )
        ->setFontShadow( '#333333', -2, 2 )
        ->setOverlayColor( '#FF0000' )
        ->setBorder( 'black', 1 )
        // Title
        ->setFont( __DIR__ . "/fonts/Roboto-Regular" )
        ->setFontSize( 40 )
        ->setFontColor( '#FFFFFF' )
        ->addText( 'Microsoft buying GitHub' )
        ->addText( '' )
        // Subtitle
        ->setFont( __DIR__ . "/fonts/Blogger_Sans.otf" )
        ->setFontSize( 20 )
        ->setFontColor( '#00FFFF' )
        ->addText( 'The deal is concluded' )
        // Watermark
        ->setTextBackground( 'black', 50 )
        ->setHorizontalAlignment( 'right' )
        ->setVerticalAlignment( 'bottom' )
        ->setFontSize( 14 )
        ->setFontColor( '#FFFFFF' )
        ->setFontShadow( '' )
        ->setFontStroke( 'black' )
        ->addText( 'http://news.com' );
          
        // Poster output
        echo $poster->saveToBase64Image( );
```
