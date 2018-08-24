<?php
namespace PosterGen;

class PosterGen
{
	use Options;
	use Utils;
	use Draw;

	function __construct( array $options = [ ] )
	{

	}

	/**
	 * 
	 */
	function getLastTextCoordinates( )
	{
		return $lastTextCoordinate;
	}

	/**
	 * 
	 */
	function getLastImageCoordinates( )
	{
		return $lastImageCoordinate;
	}

	/**
	 * 
	 */
	function addText( string $text, string $font = '', int $size = 0, string $color = '', array $values = [ ] )
	{
		// Font params
		$fontFile = ( !empty( $font ) ? $font : $this->font );
		$fontFile .= empty( pathinfo( $fontFile )[ 'extension' ] ) ? '.ttf' : '';
		$fontColor = ( !empty( $color ) ? $color : $this->fontColor );
		$fontSize = ( $size > 0 ? $size : $this->fontSize );

		// Angle
		$angle = array_key_exists( 'angle', $values ) ? $values[ 'angle' ] : 0;
		$transparent = array_key_exists( 'transparent', $values ) ? $values[ 'transparent' ] : 100;

		// Text background
		$background = [ 
			'color'			=> ( array_key_exists( 'background', $values ) && array_key_exists( 'color', $values[ 'background' ] ) ) ? $values[ 'background' ][ 'color' ] : $this->textBackgroundColor,
			'transparent'	=> ( array_key_exists( 'background', $values ) && array_key_exists( 'transparent', $values[ 'background' ] ) ) ? $values[ 'background' ][ 'transparent' ] : $this->textBackgroundTransparent
		];

		// Position values
		$position = $values[ 'position' ];
		if( empty( $position[ 'vertical-alignment' ] ) && empty( $position[ 'x' ] ) && empty( $position[ 'y' ] ) ){ $position[ 'vertical-alignment' ] = $this->verticalAlignment; };
		if( empty( $position[ 'horizontal-alignment' ] ) && empty( $position[ 'x' ] ) && empty( $position[ 'y' ] ) ){ $position[ 'horizontal-alignment' ] = $this->horizontalAlignment; };

		// Calculate coordinates
		$coordinate = $this->calculateTextCoordinates( $text, $fontSize, $position, $fontColor, $angle, $fontFile );

		//
		$data = array_replace_recursive( [
			'type'			=> 'text',
			'text'			=> $text,
			'font'			=> $fontFile,
			'font-size'		=> $fontSize,
			'color'			=> $fontColor,
			'stroke'		=> [ 
				'color' 	=> $this->strokeColor,
				'size'		=> $this->strokeSize
			],
			'size'			=> [
				'width'		=> $coordinate[ 'width' ],
				'height'	=> $coordinate[ 'height' ],
			],
			'position'		=> $position,
			'coordinate'	=> $coordinate,
			'shadow'		=> [ 
				'color' 	=> $this->shadowColor,
				'offset'	=> $this->shadowOffset
			],
			'angle'			=> $angle,
			'transparent'	=> $transparent,
			'background'	=> $background
		], $values );

		//
		array_push( $this->objectList, $data );
		
		return $this;
	}

	/**
	 * 
	 */
	function addImage( string $image, array $values = [ ] )
	{
		if( !file_exists( $image ) )
		{
			throw new \Exception( "PosterGen: No image available: {$image}!" );
		}

		// Image
		$customImage = imageCreateFromString( file_get_contents( $image ) );

		// Angle
		$angle = array_key_exists( 'angle', $values ) ? $values[ 'angle' ] : 0;
		$inline = array_key_exists( 'inline', $values ) ? $values[ 'inline' ] : false;
		$transparent = array_key_exists( 'transparent', $values ) ? $values[ 'transparent' ] : 100;

		// Image size
		$size = $this->calculateImageSize( $customImage );

		// Position values
		$position = $values[ 'position' ];
		if( empty( $position[ 'vertical-alignment' ] ) && empty( $position[ 'x' ] ) && empty( $position[ 'y' ] ) ){ $position[ 'vertical-alignment' ] = $this->verticalAlignment; };
		if( empty( $position[ 'horizontal-alignment' ] ) && empty( $position[ 'x' ] ) && empty( $position[ 'y' ] ) ){ $position[ 'horizontal-alignment' ] = $this->horizontalAlignment; };		
		$x = empty( $position[ 'x' ] ) ? $position[ 'x' ] : 0;
		$y = empty( $position[ 'y' ] ) ? $position[ 'y' ] : 0;

		//
		$data = array_replace_recursive( [
			'type'			=> 'image',
			'image'			=> $image,
			'size'			=> $size,
			'position'		=> $position,
			'coordinate'	=> [
				'top'		=> $x,
				'bottom'	=> $x + $size[ 'height' ],
				'left'		=> $y,
				'right'		=> $y + $size[ 'width' ],
			],
			'angle'			=> $angle,
			'transparent'	=> $transparent,
			'inline'		=> false
		], $values );

		//
		array_push( $this->objectList, $data );
		
		return $this;
	}

	/**
	 * 
	 */
	function save( string $format = 'png' )
	{
		header( "Content-type: image/{$format}" );

		//
		$this->generate( $format );
	}

	/**
	 * 
	 */
	function saveToBase64( string $format = 'png' )
	{
		return 'data:image/png;base64,' . base64_encode( $this->print( ) );
	}

	/**
	 * 
	 */
	function saveToBase64Image( string $format = 'png' )
	{
		return '<img src="' . $this->saveToBase64( ) . '"/>';
	}

	/**
	 * 
	 */
	function print( string $format = 'png' )
	{
		ob_start( );

		//
		$this->generate( $format );

		//
		$imageData = ob_get_contents( );
		ob_end_clean( );

		//
		return $imageData;
	}
}