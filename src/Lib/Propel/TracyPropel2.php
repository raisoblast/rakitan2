<?php declare(strict_types=1);

namespace Rakitan\Lib\Propel;

use Tracy\IBarPanel;
use Propel\Runtime\Connection\ConnectionInterface;
use Monolog\Handler\AbstractHandler;
use Monolog\Handler\HandlerInterface;

/**
 * panel propel2 untuk tracy debugger
 * referensi: https://github.com/livioribeiro/NettePropel2
 * @author arifk
 *
 */
class TracyPropel2 extends AbstractHandler implements IBarPanel, HandlerInterface
{
    protected $con;
    public $disabled = FALSE;
    private $queries = [];
    private $totalTime = 0;

    public function __construct(ConnectionInterface $con)
    {
        $this->con = $con;
    }

    public function getTab()
    {
        $queryCount = $this->con ? $this->con->getQueryCount() : 0;
        return '<span title="Propel2">'
                //. '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABmJLR0QAAAAAAAD5Q7t/AAAACXBIWXMAAAb2AAAG9gEMFeMTAAAAB3RJTUUH3gIGFCwJ4IkD5QAAAapJREFUOMudk89qE2EUxX/ffIma0MRgjCUVSU1LNo0bNSBCoU/kE/gKPoL4ECJF/LOwiohQlSKCTYm60DYhxknSmU5m8h0XLUqr0bRndRfnnHsPnIukOUmPdDwkkp5IqnIwnBRvjSQxLRTjRl00HmCzC4BHaiqdC3FBk/jHC+L2fXB75K49BPM/A7dH4r8m7q4y6jyAcYiXWSBTvQ1mX/r3CEpIes+Je09xQQtsjlT+Kl6mgs1cxssu/qIeMRBu1MYNP+Di7xibxcsu4rldTLgOg5dQvgWZ+j5bOhrBYGwOL3cFmy5ioha074L/DML3kF+BdPk325gJEaIv8PUO+I8hVYDcDcgvw8xNSF84vPKQQdKDzj0INmCmAWdX4HSF4XBAFCWcK17EGDPBYNyH8COcmYfUeZwbs73dYmvrHb3eN2q1BrXadTzP/uMCIE5GdNqf2dx8Q7/fxdoU5XKVen2ZdPrUH2nNQSVJkhG+36XZXCcIfEqlS8zOzlMszmHt5LoYSUkUhXZn5xNRtEupVKFQKE1dbiStRlFw0md6haQlSWuS3DHfeU1S4ycDPKz+zZXMvQAAAABJRU5ErkJggg==" />'
                . '<img width="16" height="16" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAEYSURBVBgZBcHPio5hGAfg6/2+R980k6wmJgsJ5U/ZOAqbSc2GnXOwUg7BESgLUeIQ1GSjLFnMwsKGGg1qxJRmPM97/1zXFAAAAEADdlfZzr26miup2svnelq7d2aYgt3rebl585wN6+K3I1/9fJe7O/uIePP2SypJkiRJ0vMhr55FLCA3zgIAOK9uQ4MS361ZOSX+OrTvkgINSjS/HIvhjxNNFGgQsbSmabohKDNoUGLohsls6BaiQIMSs2FYmnXdUsygQYmumy3Nhi6igwalDEOJEjPKP7CA2aFNK8Bkyy3fdNCg7r9/fW3jgpVJbDmy5+PB2IYp4MXFelQ7izPrhkPHB+P5/PjhD5gCgCenx+VR/dODEwD+A3T7nqbxwf1HAAAAAElFTkSuQmCC" />'
                . '&nbsp;' . $queryCount . ' | ' . $this->totalTime . 'ms'
                . '</span>';
    }

    public function getPanel()
    {
        ob_start(function(){});
        $queries = $this->queries;
        require __DIR__ . '/propel2-panel.php';
        return ob_get_clean();
    }

    public function logQuery($message)
    {
        if ($this->disabled) {
            return;
        }
        list($time, $memory, $memoryDelta, $memoryPeak, $query) = explode('|', $message);
        $time = trim($time, 'Time: ms');
        $this->totalTime += floatval($time);
        $memory = trim($memory, 'Memory: ');
        $memoryDelta = trim($memoryDelta, 'Memory Delta: ');
        $memoryPeak = trim($memoryPeak, 'Memory Peak: ');
        $query = trim($query);
        $this->queries[] = [
            'time' => $time,
            'memory' => trim($memory),
            'memoryDelta' => trim($memoryDelta),
            'memoryPeak' => trim($memoryPeak),
            'query' => $query
        ];
    }

    public function logError($message) {
        $this->queries[] = ['time' => '', 'memory' => '', 'memoryDelta' => '',
                'memoryPeak' => '', 'query' => trim($message)];
    }

    public function handle(array $record)
    {
        if ($record['level'] == \Monolog\Logger::INFO) {
            $this->logQuery($record['message']);
        } else {
            $this->logError($record['message']);
        }
    }
}