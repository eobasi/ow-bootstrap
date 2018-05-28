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

class BOOTSTRAP_CTRL_Admin extends ADMIN_CTRL_Abstract
{
	public function __construct()
	{
		parent::__construct();

		if ( OW::getRequest()->isAjax() )
		{
			return;
		}

		$this->lang = OW::getLanguage();
		$menu = $this->menu();
		
		$this->addComponent('menu', $menu);		
	}

    public function settings()
    {
		$config = OW::getConfig();
        
		$this->setPageTitle($this->lang->text('bootstrap', 'admin_page_title'));
		$this->setPageHeading(OW::getLanguage()->text('bootstrap', 'admin_settings_heading'));
        $this->setPageHeadingIconClass('ow_ic_gear_wheel');
		
		$form = new Form('bootstrap_settings');
		
		$fieldVersion = new Selectbox('version');
		$fieldVersion->setLabel($this->lang->text('bootstrap', 'version_label'));
		$fieldVersion->setDescription($this->lang->text('bootstrap', 'version_desc'));
		$fieldVersion->setOptions(array(
			BOOTSTRAP_CLASS_EventHandler::VERSION_4_0 => BOOTSTRAP_CLASS_EventHandler::VERSION_4_0,
			BOOTSTRAP_CLASS_EventHandler::VERSION_3_3_7 => BOOTSTRAP_CLASS_EventHandler::VERSION_3_3_7,
		));
		$fieldVersion->setValue($config->getValue('bootstrap', 'version'));
		$form->addElement($fieldVersion);
		
		$fieldCssMaxcdn = new CheckboxField('css_maxcdn');
		$fieldCssMaxcdn->setLabel($this->lang->text('bootstrap', 'css_maxcdn_label'));
		$fieldCssMaxcdn->setDescription($this->lang->text('bootstrap', 'css_maxcdn_desc'));
		$fieldCssMaxcdn->setValue($config->getValue('bootstrap', 'css_maxcdn'));
		$form->addElement($fieldCssMaxcdn);
		
		$fieldJsMaxcdn = new CheckboxField('js_maxcdn');
		$fieldJsMaxcdn->setLabel($this->lang->text('bootstrap', 'js_maxcdn_label'));
		$fieldJsMaxcdn->setDescription($this->lang->text('bootstrap', 'js_maxcdn_desc'));
		$fieldJsMaxcdn->setValue($config->getValue('bootstrap', 'js_maxcdn'));
		$form->addElement($fieldJsMaxcdn);
		
		$fieldCssOrder = new RadioField('css_order');
		$fieldCssOrder->setLabel($this->lang->text('bootstrap', 'css_order_label'));
		$fieldCssOrder->setDescription($this->lang->text('bootstrap', 'css_order_desc'));
		$fieldCssOrder->setOptions(array(
			'before' => $this->lang->text('bootstrap', 'admin_option_before'),
			'after' => $this->lang->text('bootstrap', 'admin_option_after'))
		);
		$fieldCssOrder->setValue($config->getValue('bootstrap', 'css_order'));
		$form->addElement($fieldCssOrder);
		
		$fieldCustomCss = new Textarea('custom_css');
        $fieldCustomCss->setLabel($this->lang->text("bootstrap", "custom_css_label"));
        $fieldCustomCss->setHasInvitation(true);
        $fieldCustomCss->setInvitation($this->lang->text('bootstrap', 'custom_css_example'));
        $fieldCustomCss->setDescription($this->lang->text('bootstrap', 'custom_css_desc'));
		$fieldCustomCss->setValue($config->getValue('bootstrap', 'custom_css'));
		$form->addElement($fieldCustomCss);
		
		$buttonSave = new Submit('save');
		$buttonSave->setValue($this->lang->text('bootstrap', 'admin_button_save_value'));
		$form->addElement($buttonSave);
		
		if ( OW::getRequest()->isPost() && $form->isValid($_POST) )
		{
			$data = $form->getValues();
			
			$config->saveConfig('bootstrap', 'version', trim($data['version']));
			$config->saveConfig('bootstrap', 'css_maxcdn', trim($data['css_maxcdn']));
			$config->saveConfig('bootstrap', 'js_maxcdn', trim($data['js_maxcdn']));
			$config->saveConfig('bootstrap', 'css_order', trim($data['css_order']));
			$config->saveConfig('bootstrap', 'custom_css', trim($data['custom_css']));
			
			OW::getFeedback()->info($this->lang->text('bootstrap', 'admin_successfully_saved'));
			
			$this->redirect();
		}
		
		$this->addForm( $form );
		
		$img = OW::getPluginManager()->getPlugin('bootstrap')->getStaticUrl() . 'img/bootsrap.png';
		$this->assign('img', $img);
    }
	
	public function spotlight()
    {
		$config = OW::getConfig()->getValues('base');
		
		$this->setPageTitle($this->lang->text('bootstrap', 'spotlight_title'));
		$this->setPageHeading($this->lang->text('bootstrap', 'admin_donate_heading'));
        $this->setPageHeadingIconClass('ow_ic_gear_wheel');
		
		$uri = OW::getRequest()->buildUrlQueryString(BOOTSTRAP_CLASS_EventHandler::SPOTLIGHT, array(
			'u'=> OW::getRouter()->getBaseUrl(),
			's'=> base64_encode( $config['soft_version'] ),
			'b'=> base64_encode( $config['soft_build'] ),
			'n'=> base64_encode( $config['site_name'] ),
			't'=> base64_encode( $config['selectedTheme'] ),
			'e'=> base64_encode( $config['site_email'] )
		));
		
		$this->assign('url', $uri);
	}
	
	private function menu()
	{
		$menu = new BASE_CMP_ContentMenu();

		$menuItem = new BASE_MenuItem();
		$menuItem->setKey('settings');
		$menuItem->setLabel($this->lang->text('bootstrap', 'bootstrap_admin_settings'));
		$menuItem->setUrl(OW::getRouter()->urlForRoute('bootstrap_admin_settings'));
		$menuItem->setIconClass('ow_ic_gear_wheel');
		$menuItem->setOrder(1);
		$menu->addElement($menuItem);

		$menuItem = new BASE_MenuItem();
		$menuItem->setKey('spotlight');
		$menuItem->setLabel($this->lang->text('bootstrap', 'bootstrap_admin_donate'));
		$menuItem->setUrl(OW::getRouter()->urlForRoute('bootstrap_admin_spotlight'));
		$menuItem->setIconClass('ow_ic_star');
		$menuItem->setOrder(2);
		$menu->addElement($menuItem);
		
		return $menu;
	}
}