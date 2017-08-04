{*
* 2007-2015 PrestaShop
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
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<div class="row">
	<div class="col-xs-12 col-md-6">
        <p class="payment_module">
            <a class="bg-red-multi-paiement" href="{$link->getModuleLink('xcheque', 'payment', [], true)|escape:'html':'UTF-8'}" style="
    width: 100%;text-align:center;font-size:20px;    color: white;
    padding-left: 0;
" title="{l s='Paiement différé, offre spéciale Hiver' mod='xcheque'}">
								{l s='Commencez à payer dans 6 mois' mod='xcheque'} <span>{l s='' mod='xcheque'}</span>
            </a>
        </p>
    </div>
</div>
<div class="row">
	<div class="col-xs-12 col-md-6">
        <p class="payment_module">
            <a class="cheque img_multi-payment" href="{$link->getModuleLink('xcheque', 'payment', [], true)|escape:'html':'UTF-8'}" style="
    padding-left: 40%;
    width: 100%;
" title="{l s='Réglez en 12, 24 fois ou plus ...' mod='xcheque'}">
								{l s='Réglez en 12, 24 fois ou plus , à voir avec nos conseillers.' mod='xcheque'} <span>{l s='' mod='xcheque'}</span>
            </a>
        </p>
    </div>
</div>
