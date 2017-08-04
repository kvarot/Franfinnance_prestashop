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

{capture name=path}{l s='Règlement par chèque en trois fois sans frais' mod='xcheque'}{/capture}

<h1 class="page-heading">{l s='Récapitulatif de votre commande' mod='xcheque'}</h1>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{if isset($nbProducts) && $nbProducts <= 0}
	<p class="alert alert-warning">{l s='Votre panier est vide.' mod='xcheque'}</p>
{else}

	<form action="{$link->getModuleLink('xcheque', 'validation', [], true)|escape:'html':'UTF-8'}" method="post">
		<div class="box xcheque-box">
			<h3 class="page-subheading">{l s='Solutions de paiements multiples' mod='xcheque'}</h3>
			<p class="xcheque-indent">
				<strong class="dark">
					{l s='Vous avez choisi le paiement en plusieurs mensualités, voici ce qui va se passer a partir de maintenant.' mod='xcheque'}
				</strong>
			</p><br />
			<div class="col-md-12">

</div>
{* Short explanation for the three times payment by check *}
			<p>{l s='Vous allez etre contacté par téléphone par Franfinance pour monter votre dossier, vous choisirez alors le montant et le nombre des mensualités qui vous conviennent le mieux.' mod='xcheque'}</p><br />
			<p>{l s='Franfinance vous enverra ensuite votre contrat par email.' mod='xcheque'}</p><br />
			<p>{l s='Vous le renvoyez par la poste sans affranchissement à Franfinance.' mod='xcheque'}</p><br />
			<p>{l s='Dès que Franfinance nous confirme réception de votre contrat, nous vous expédions votre Hussarde.' mod='xcheque'}</p><br />
			<p>{l s='Avec votre Hussarde vous recevrez une attestation de livraison que vous nous retournerez signée dans une enveloppe préaffranchie.' mod='xcheque'}<p>
			<br />

			<p class="xcheque-indent">
				<strong class="dark">
				  {l s='Voici un bref récapitulatif de votre commande:' mod='xcheque'}
				</strong>
			</p>

{* total order *}
			<p>
				- {l s='Le montant total de votre commande s\'élève à:' mod='xcheque'}
				<span id="amount" class="price">{displayPrice price=$total}</span>
				{if $use_taxes == 1}
					{l s='(TTC)' mod='xcheque'}
				{/if}
			</p>

{* Each check amount *}
			
			<p>

				{if isset($currencies) && $currencies|@count > 1}


					<div class="form-group">
						<label>{l s='Nous acceptons plusieurs devises pour votre paiement par chèque.' mod='xcheque'}</label>
						<p>- {l s='Merci de choisir celle qui vous convient:' mod='xcheque'}</p>
						<select id="currency_payment" class="form-control" name="currency_payment">
						{foreach from=$currencies item=currency}
							<option value="{$currency.id_currency}"{if isset($currencies) && $currency.id_currency == $cust_currency} selected="selected"{/if}>{$currency.name}</option>
						{/foreach}
						</select>
					</div>
				{else}
					{l s='Nous acceptons la devise suivante pour votre paiement:' mod='xcheque'}&nbsp;<b>{$currencies.0.name}</b>
					<input type="hidden" name="currency_payment" value="{$currencies.0.id_currency}" />
				{/if}
			</p>
			<p>
				- {l s='Un email reprenant ces explications vous a été envoyé.' mod='xcheque'}
			</p>
			<p>
				- {l s='
				
Attendez vous donc à recevoir sous peu un appel de Franfinance.
				' mod='xcheque'}
			</p>
			<p>
				- {l s='
				En vous remerciant de votre confiance.
				' mod='xcheque'}
			</p>
			<p>
				- {l s='
				Equipe NaitUp
				' mod='xcheque'}
			</p>
			<p>
				- {l s='Merci de confirmer votre commande en cliquant sur \'Je confirme ma commande\'.' mod='xcheque'}
			</p>
		</div>
		<p class="cart_navigation clearfix" id="cart_navigation">
			<a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}" class="button-exclusive btn btn-default">
				<i class="icon-chevron-left"></i>{l s='Autres méthodes de paiement' mod='xcheque'}
			</a>
			<button type="submit" class="button btn btn-default button-medium">
				<span>{l s='Je confirme ma commande' mod='xcheque'}<i class="icon-chevron-right right"></i></span>
			</button>
		</p>
	</form>
{/if}
