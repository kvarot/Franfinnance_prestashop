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

{if $status == 'ok'}
	<p class="alert alert-success">{l s='Votre commande sur %s a bien été enregistrée.' sprintf=$shop_name mod='xcheque'}</p>
	<div class="box order-confirmation">
		<h3 class="page-subheading">{l s='Le montant total de votre commande s\'élève à :' mod='xcheque'} <class="price"><strong>{$total_to_pay}</strong>
			<h5> {l s='Vous avez choisi le règlement multiple.' mod='xcheque'}
				<br />
			</h5>
		</h3>
<div class="img-partenaire-payment"><div class="row"><div class="col-md-5"><img class="logo img-responsive" src="https://boutique.naitup.fr/img/Snaitup-logo-1460025404.jpg" alt="dev.naitup.fr" width="350" height="97"></div><div class="col-md-5"><img class="logo img-responsive" src="http://boutique.naitup.fr/img/cms/icone-location/franfinance@2X.jpg" alt="dev.naitup.fr" width="350" height="97" style="
    float: right;
"></div></div></div>
		<p>- {l s='Société :' mod='xcheque'} <strong>Naitup</strong></p>
		<p>- {l s='Adresse :' mod='xcheque'} <strong>235 avenue des chênes rouges, 30100 Alès France</strong></p>
		<p>- {l s='Téléphone :' mod='xcheque'} <strong>(+33) 09 81 86 04 93</strong></p>
		<p>- {l s='Email :' mod='xcheque'} <strong>contact@naitup.com</strong></p>
		{if !isset($reference) && isset($id_order) && $id_order}
			<p>- {l s='Pensez à indiquer le numéro de votre commande: #%d.' sprintf=$id_order mod='xcheque'}</p>
		{else}
			<p>- {l s='Pensez à indiquer la référence de votre commande: %s.' sprintf=$reference mod='xcheque'}</p>
		{/if}
		
		<p><strong>{l s='Votre commande sera traitée dés réception de votre paiement.' mod='xcheque'}</strong></p>
		<p>- {l s='Pour toutes autres questions, merci de contacter notre' mod='xcheque'} <a href="http://boutique.naitup.fr/forms/1/nous-contacter">{l s='service client.' mod='xcheque'}</a>.</p>
	</div>
{else}
	<p class="alert alert-warning">
		{l s='Nous avons rencontré un problème avec votre commande. Si vous pensez que c\'est une erreur, contactez, s\'il-vous-plait, notre' mod='xcheque'}
		<a href="{$link->getPageLink('contact', true)|escape:'html':'UTF-8'}">{l s='service client.' mod='xcheque'}</a>.
	</p>
{/if}
