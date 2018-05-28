<?php

/**
 * This software is intended for use with Oxwall Free Community Software http://www.oxwall.org/ and is a proprietary licensed product.
 * For more information see License.txt in the plugin folder.

 * ---
 * Copyright (c) 2018, Ebenezer Obasi
 * All rights reserved.
 * info@eobasi.com.

 * Redistribution and use in source and binary forms, with or without modification, are not permitted provided.

 * This plugin should be bought from the developer. For details contact info@eobasi.com.

 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
 * INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

/**
 * @author Ebenezer Obasi <info@eobasi.com>
 * @package ow_plugins.bootstrap.classes
 * @since 1.0
 */

class BOOTSTRAP_CLASS_EventHandler
{
	const SPOTLIGHT = 'http://spotlight.ewtnet.us/';
	const CDN = 'https://maxcdn.bootstrapcdn.com/bootstrap/';

	const VERSION_4_0 = '4.0.0';
	const VERSION_3_3_7 = '3.3.7';

    private static $classInstance;

    public static function getInstance()
    {
        if ( self::$classInstance === null )
        {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    public function init()
    {
        OW::getEventManager()->bind(OW_EventManager::ON_BEFORE_DOCUMENT_RENDER, array($this, 'onBeforeDocumentRender'));
    }

	public function onBeforeDocumentRender()
	{
		$config = OW::getConfig()->getValues('bootstrap');		
		$order = -999;
		
		switch($config['css_order'])
		{
			case 'after':
				$order = 1000;
				break;
			case 'before':
				$order = -999;
				break;			 
		}
		
		//Add Bootstrap Stylesheet
		OW::getDocument()->addStyleSheet($this->staticUrl( 'css', $config['version'], (bool)$config['css_maxcdn'] ), 'all', $order);
		
		//Add Bootstrap JS
		OW::getDocument()->addScript( $this->staticUrl( 'js', $config['version'], (bool)$config['js_maxcdn'] ), 'text/javascript', 1);

		//Add custom CSS
		if( !empty( $config['custom_css'] ) )
		{
			OW::getDocument()->addStyleDeclaration( $config['custom_css'] );
		}
	}
	
	private function staticUrl( $type, $version, $cdn )
	{
		$pluginStaticUrl = OW::getPluginManager()->getPlugin('bootstrap')->getStaticUrl();
		
		switch( $type )
		{
			case 'js':
				$staticUrl = $cdn ? self::CDN : $pluginStaticUrl;
				return  $staticUrl.$version.'/js/bootstrap.min.js';
				break;
			case 'css':
				$staticUrl = $cdn ? self::CDN : $pluginStaticUrl;
				return  $staticUrl.$version.'/css/bootstrap.min.css';
				break;
		}
	}
}