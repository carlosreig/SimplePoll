<?php

class PollParser 
{

	public function __construct( SimpleXMLElement &$tree )
	{
		$this->parsePoll( $tree );
	}

	protected function parsePoll( SimpleXMLElement &$tree )
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

	protected function parseQuestions( SimpleXMLElement &$tree )
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

	public function getVarsArray()
	{
		return $this->templateArray;
	}

	protected $templateArray;
}