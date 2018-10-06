<?php

/**
 * NameSplitterParser
 *
 * PHP version 7
 *
 * @package   NameSplitterParser
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

namespace Skopenow\NameInfo\NameSplitter\Parser;

use Skopenow\NameInfo\NameSplitter\Parser\NameSplitterParserInterface;
use Skopenow\NameInfo\NameSplitter\NameSplitter;
use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterExtractNamePartsCommand;
use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterRemoveExtraNamesCommand;
use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterCombinePartsCommand;
use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterPrepareNameInputCommand;
use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterHonorificNickNamesCommand;
use Skopenow\NameInfo\NameSplitter\Commands\NameSplitterCommandList;
use Skopenow\NameInfo\NameSplitter\Iterator\NameSplitterIterator;

/**
 * NameSplitterParserInterface
 *
 *
 * @package   NameSplitter
 * @author    Queen tech <info@queentechsolutions.net>
 * @copyright 2017-2018 Queen tech
 * @access    public
 * @license   http://www.queentechsolutions.net/ BSD Licence
 * @version   CVS: 1.0.0
 * @link      http://www.queentechsolutions.net/
 */

class NameSplitterParser implements NameSplitterParserInterface
{
    /**
     * $name
     * 
     * @var array 
     */
    private $names;

    /**
     * $iterator
     * 
     * @var NameSplitterIterator 
     */
    private $iterator;

    /**
     * $commandList
     * 
     * @var NameSplitterCommandList 
     */
    private $commandList;

    /**
     * Constructor
     * 
     * @param array $names
     * @param NameSplitterIterator $iterator
     */
    public function __construct(\Iterator $names, NameSplitterCommandList $commandList, NameSplitterIterator $iterator)
    {
        $this->names = $names;
        $this->iterator = $iterator;
        $this->commandList = $commandList;
    }

    /**
     * process
     *
     * processes the names
     * 
     * @return iterator
     */
    public function process() : \Iterator
    {
        foreach ($this->names as $name) {
            $nameSplitter = new NameSplitter($name);
            $this->commandList->addCommand(new NameSplitterPrepareNameInputCommand(), $nameSplitter);
            $this->commandList->addCommand(new NameSplitterHonorificNickNamesCommand(true), $nameSplitter);
            $this->commandList->addCommand(new NameSplitterExtractNamePartsCommand(), $nameSplitter);
            $this->commandList->addCommand(new NameSplitterRemoveExtraNamesCommand(), $nameSplitter);
            $this->commandList->addCommand(new NameSplitterCombinePartsCommand(), $nameSplitter);
            $this->commandList->run();

            $this->iterator->addName($nameSplitter->getProcessedName());
        }

        return $this->iterator->getIterator();
    }
}