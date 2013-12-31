<?php

use Nette\Utils\Finder;


class HomepagePresenter extends Schmutzka\Application\UI\Presenter
{
	/** @persistent @var [] */
	public $filter = [];

	/** @persistent @var string */
	public $command;

	/** @inject @var Components\IGaControl */
	public $gaControl;


	public function startup()
	{
		parent::startup();

		if ($this->command == NULL) {
			$this->redirect('this', ['command' => 'where']);
		}

		if ($this->filter == NULL) {
			$this->redirect('this', ['filter[]' => 'sql']);
		}
	}


	public function createTemplate($class = NULL)
	{
		$template = parent::createTemplate($class);
		$template->registerHelper('highlight', function ($code) {
			$code = '<?php ' . $code;
			$split = array('&lt;?php&nbsp;' => '');
			return strtr(highlight_string($code, TRUE), $split);
		});

		return $template;
	}


	/**
	 * @param string
	 */
	public function handleAddToFilter($key)
	{
		$this->filter[] = $key;
		$this->redirect('this');
	}


	/**
	 * @param string
	 */
	public function handleRemoveFromFilter($key)
	{
		if (($key = array_search($key, $this->filter)) !== FALSE) {
			unset($this->filter[$key]);
		}

		$this->redirect('this');
	}


	public function renderDefault()
	{
		$this->template->filter = $this->filter;
		$this->template->activeCommand = $this->command;
		$this->template->typeList = $this->paramService->typeList;
		$this->template->commandList = $this->paramService->commandList;
		$this->template->codeList = $this->getCodeList();
	}


	/**
	 * @return []
	 */
	private function getCodeList()
	{
		$data = [];
		foreach ($this->paramService->typeList as $key => $value) {
			$files = Finder::findFiles('*')->in($this->paramService->appDir . '/config/syntax/' . $key);
			foreach ($files as $value) {
				$data[$key][$value->getBasename('.neon')] = trim(file_get_contents($value));
			}
		}

		return $data;
	}

}
