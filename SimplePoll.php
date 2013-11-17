<?php
require 'vendor/autoload.php';

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

		$this->parsePoll($xmlTree);
	}

	public function parsePoll( SimpleXMLElement $tree )
	{
		if ( $tree->getName() != 'poll' )
			throw new Exception('You have to pass a SimpleXMLElement with \'poll\' as root element ');

		foreach( $tree->children() as $element )
		{
			switch ( $element->getName() )
			{
				case 'title':
					$this->templateArray['title'] = (string) $element;
				break;

				case 'description':
					$this->templateArray['description'] = (string) $element;
				break;

				case 'questions':
					$this->parseQuestions( $element );
				break;
			}
		}
	}

	public function parseQuestions( SimpleXMLElement $tree )
	{
		if ( $tree->getName() != 'questions' )
			throw new Exception('You have to pass a SimpleXMLElement with \'questions\' as root element ');

		foreach( $tree->children() as $element )
		{
			switch ( $element->getName() )
			{
				case 'choice':
					$choice = new ChoiceField( $element );
					$this->templateArray['questions'][] = $choice->getTemplateVars();
				break;

				case 'rating':
					$rating = new RatingField( $element );
					$this->templateArray['questions'][] = $rating->getTemplateVars();
				break;

				case 'text':
					$text = new TextField( $element );
					$this->templateArray['questions'][] = $text->getTemplateVars();
				break;
			}
		}
	}

	public function renderPoll( $templateName )
	{
		echo $this->twig->render( $templateName, $this->templateArray );
	}

	protected $twig;
	protected $templateArray;
}

$poll = new SimplePoll();
$poll->loadPoll('testData/poll.xml');
$poll->renderPoll('poll.twig');