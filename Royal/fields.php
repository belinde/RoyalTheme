<?php
namespace Royal\Fields;

use Royal\Fields\AbstractField as _;

return [
	( new Integer( 'prezzo' ) )
		->setAppend( '€' )
		->setEmpty( 'trattativa riservata' )
		->setSearch( _::SEARCH_RANGE )
	,
	( new Select( 'status' ) )
		->setValues( [
			'disponibile' => 'Disponibile sul mercato',
			'trattativa'  => 'In trattativa',
			'terminato'   => 'Venduto o piazzato'
		] )
		->setSearch( _::SEARCH_DISABLED )
		->setHelp( "Determina se l'annuncio è visibile o meno sul sito" )
	,
	( new Integer( 'vani' ) )
		->setSearch( _::SEARCH_RANGE )
	,
	( new Integer( 'superficie' ) )
		->setAppend( 'm&sup2;' )
		->setSearch( _::SEARCH_RANGE )
	,
	( new Text( 'condizione' ) )
		->setSearch( _::SEARCH_DISABLED )
	,
	( new Integer( 'bagni', "Numero bagni" ) )
		->setSearch( _::SEARCH_RANGE )
	,
	( new Integer( 'balconi' ) )
		->setEmpty( 'nessuno' )
		->setSearch( _::SEARCH_RANGE )
	,
	( new Integer( 'terrazzi' ) )
		->setEmpty( 'nessuno' )
		->setSearch( _::SEARCH_RANGE )
	,
	( new Select( 'riscaldamento' ) )
		->setValues( [
			'assente'      => 'Assente',
			'condominio'   => 'Condominiale',
			'indipendente' => 'Termoautonomo'
		] )
		->setSearch( _::SEARCH_RANGE )
	,
	( new Select( 'condizionatore', "Aria condizionata" ) )
		->setValues( [
			'assente'     => 'Assente',
			'predisposto' => 'Con predisposizione',
			'presente'    => 'Presente'
		] )
		->setSearch( _::SEARCH_EXACT )
	,
	( new Boolean( 'ascensore' ) )
		->setSearch( _::SEARCH_EXACT )
	,
	( new Text( 'posteggio', "Posto auto" ) )
		->setSearch( _::SEARCH_DISABLED )
	,
	( new LongText( 'indirizzo', "Indirizzo completo" ) )
		->setSearch( _::SEARCH_DISABLED )
];