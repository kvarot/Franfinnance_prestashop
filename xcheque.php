<?php
/*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;

class Xcheque extends PaymentModule
{
	private $_html = '';
	private $_postErrors = array();

	public $xchequeName;
	public $address;
	public $dispo;
	public $extra_mail_vars;

	public function __construct()
	{
		$this->name = 'xcheque';
		$this->tab = 'payments_gateways';
		$this->version = '3.1';
		$this->author = 'JazZ';
		$this->controllers = array('payment', 'validation');
		$this->is_eu_compatible = 1;

		$this->currencies = true;
		$this->currencies_mode = 'checkbox';

		$config = Configuration::getMultiple(array('XCHEQUE_NAME', 'XCHEQUE_ADDRESS'));
		if (isset($config['XCHEQUE_NAME']))
			$this->xchequeName = $config['XCHEQUE_NAME'];
		if (isset($config['XCHEQUE_ADDRESS']))
			$this->address = $config['XCHEQUE_ADDRESS'];

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('xcheque');
		$this->description = $this->l('Ce module vous permet d\'accepter les règlements par chèque en trois fois sans frais.');
		$this->confirmUninstall = $this->l('Êtes vous sûr de vouloir supprimer les détails enregistrés?');

		if ((!isset($this->xchequeName) || !isset($this->address) || empty($this->xchequeName) || empty($this->address)))
			$this->warning = $this->l('Les cases "Payer à l\'ordre de" et "Adresse" doivent être remplies avant de pouvoir utiliser ce module.');
		if (!count(Currency::checkPaymentCurrencies($this->id)))
			$this->warning = $this->l('Aucune devise n\'a été configurée pour ce module');

		$this->extra_mail_vars = array(
											'{xcheque_name}' => Configuration::get('XCHEQUE_NAME'),
											'{xcheque_address}' => Configuration::get('XCHEQUE_ADDRESS'),
											'{xcheque_address_html}' => str_replace("\n", '<br />', Configuration::get('XCHEQUE_ADDRESS'))
											);
	}

	public function install()
	{
		if (!parent::install() || !$this->registerHook('payment') || ! $this->registerHook('displayPaymentEU') || ! $this->registerHook('dispo') || !$this->registerHook('paymentReturn'))
			return false;
		return true;
	}

	public function uninstall()
	{
		if (!Configuration::deleteByName('XCHEQUE_NAME') || !Configuration::deleteByName('XCHEQUE_ADDRESS') || !parent::uninstall())
			return false;
		return true;
	}

	private function _postValidation()
	{
		if (Tools::isSubmit('btnSubmit'))
		{
			if (!Tools::getValue('XCHEQUE_NAME'))
				$this->_postErrors[] = $this->l('La case "Payer à l\'ordre de" doit être complétée.');
			elseif (!Tools::getValue('XCHEQUE_ADDRESS'))
				$this->_postErrors[] = $this->l('La case "Adresse" doit être complétée.');
		}
	}

	private function _postProcess()
	{
		if (Tools::isSubmit('btnSubmit'))
		{
			// create new order status XCHEQUE
				$orderState = new OrderState();
				$orderState->name = array();
				$orderState->template = array();

				foreach (Language::getLanguages() AS $language)
				{
						$orderState->name[$language['id_lang']] = 'En attente des chèques';
						$orderState->template[$language['id_lang']] = 'xcheque';
				}

				$orderState->send_email = true;
				$orderState->color      = '#f2ff05';
				$orderState->unremovable = false;
				$orderState->hidden     = false;
				$orderState->delivery   = false;
				$orderState->logable    = false;
				$orderState->invoice    = false;

				if ($orderState->add())
				copy(dirname(__FILE__).'/logo.gif', _PS_IMG_DIR_.'os/'.$orderState->id.'.gif');

				Configuration::updateValue('PS_OS_XCHEQUE', (int)$orderState->id);
			// End of new order status

			// Copy mail templates from /modules/xcheck/mails to root/mails
				define('DS', DIRECTORY_SEPARATOR);
				define('_PS_MAILS_DIR_', _PS_ROOT_DIR_.DS.'mails'.DS);
					// FR
					if (!@copy(dirname(__FILE__).DS.'mails'.DS.'xcheque.html', _PS_MAILS_DIR_.'fr'.DS.'xcheque.html'))
						return false;
					if (!@copy(dirname(__FILE__).DS.'mails'.DS.'xcheque.txt', _PS_MAILS_DIR_.'fr'.DS.'xcheque.txt'))
						return false;
			// End of cp mail templates

			Configuration::updateValue('XCHEQUE_NAME', Tools::getValue('XCHEQUE_NAME'));
			Configuration::updateValue('XCHEQUE_ADDRESS', Tools::getValue('XCHEQUE_ADDRESS'));
		}
		$this->_html .= $this->displayConfirmation($this->l('Configuration enregistrée et état de commande créé.'));
	}

	private function _displayCheque()
	{
		return $this->display(__FILE__, 'infos.tpl');
	}

	public function getContent()
	{
		$this->_html = '';

		if (Tools::isSubmit('btnSubmit'))
		{
			$this->_postValidation();
			if (!count($this->_postErrors))
				$this->_postProcess();
			else
				foreach ($this->_postErrors as $err)
					$this->_html .= $this->displayError($err);
		}

		$this->_html .= $this->_displayCheque();
		$this->_html .= $this->_dispo();
		$this->_html .= $this->renderForm();

		return $this->_html;
	}

	public function hookPayment($params)
	{
		if (!$this->active)
			return;
		if (!$this->checkCurrency($params['cart']))
			return;

		$this->smarty->assign(array(
			'this_path' => $this->_path,
			'this_path_xcheque' => $this->_path,
			'this_path_ssl' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->name.'/'
		));
		return $this->display(__FILE__, 'payment.tpl');
		// $this->context->controller->addCSS(($this->_path).'css/xcheque.css', 'all');
	}

	public function hookDisplayPaymentEU($params)
	{
		if (!$this->active)
			return;
		if (!$this->checkCurrency($params['cart']))
			return;

		return array(
			'cta_text' => $this->l('Payer par chèque en trois fois sans frais'),
			'logo' => Media::getMediaPath(dirname(__FILE__).'/xcheque.png'),
			'action' => $this->context->link->getModuleLink($this->name, 'validation', array(), true)
		);
	}
	
	public function hookdispo ($params) {
	return $this->display(__FILE__, 'dispo.tpl');
	}

	public function hookPaymentReturn($params)
	{
		if (!$this->active)
			return;

		$state = $params['objOrder']->getCurrentState();
		if (in_array($state, array(Configuration::get('PS_OS_XCHEQUE'), Configuration::get('PS_OS_OUTOFSTOCK'), Configuration::get('PS_OS_OUTOFSTOCK_UNPAID'))))
		{
			$this->smarty->assign(array(
				'total_to_pay' => Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false),
				'each_check' => Tools::displayPrice($params['total_to_pay'] / 3),
				'xchequeName' => $this->xchequeName,
				'xchequeAddress' => Tools::nl2br($this->address),
				'status' => 'ok',
				'id_order' => $params['objOrder']->id
			));
			if (isset($params['objOrder']->reference) && !empty($params['objOrder']->reference))
				$this->smarty->assign('reference', $params['objOrder']->reference);
		}
		else
			$this->smarty->assign('status', 'failed');
		return $this->display(__FILE__, 'payment_return.tpl');
	}

	public function checkCurrency($cart)
	{
		$currency_order = new Currency((int)($cart->id_currency));
		$currencies_module = $this->getCurrency((int)$cart->id_currency);

		if (is_array($currencies_module))
			foreach ($currencies_module as $currency_module)
				if ($currency_order->id == $currency_module['id_currency'])
					return true;
		return false;
	}

	public function renderForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Coordonnées'),
					'icon' => 'icon-envelope'
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Payer à l\'ordre de'),
						'name' => 'XCHEQUE_NAME',
						'required' => true
					),
					array(
						'type' => 'textarea',
						'label' => $this->l('Addresse'),
						'desc' => $this->l('Addresse où les chèques doivent être envoyés.'),
						'name' => 'XCHEQUE_ADDRESS',
						'required' => true
					),
				),
				'submit' => array(
					'title' => $this->l('Enregistré'),
				)
			),
		);

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->id = (int)Tools::getValue('id_carrier');
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'btnSubmit';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}

	public function getConfigFieldsValues()
	{
		return array(
			'XCHEQUE_NAME' => Tools::getValue('XCHEQUE_NAME', Configuration::get('XCHEQUE_NAME')),
			'dispo' => Tools::getValue('dispo', Configuration::get('dispo')),
			'XCHEQUE_ADDRESS' => Tools::getValue('XCHEQUE_ADDRESS', Configuration::get('XCHEQUE_ADDRESS')),
		);
	}
}
