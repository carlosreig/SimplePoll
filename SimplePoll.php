<?php
require 'vendor/autoload.php';

require 'PollParser.php';
require 'PollFields.php';


class SimplePoll
{
	function __construct()
	{
		$loader = new Twig_Loader_Filesystem( 'templates' );
		$this->twig = new Twig_Environment( $loader );
	}

	public function loadPoll( $pollDescription )
	{
		$xmlTree = new SimpleXMLElement( file_get_contents( $pollDescription ) );
		$this->pollParser = new PollParser( $xmlTree );
	}

	public function render( $templateName )
	{
		if ( !$_POST )
			$this->renderPoll( $templateName );
		else
			$this->processResults();
	}

	protected function renderPoll( $templateName )
	{
		echo $this->twig->render( $templateName, $this->pollParser->getVarsArray() );
	}

	protected function processResults()
	{
		echo 'Gracias!';
	}

	protected $twig;
	protected $pollParser;
}

$poll = new SimplePoll();
$poll->loadPoll('testData/poll.xml');

$poll->render('poll.twig');