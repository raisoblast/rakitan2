<?php declare(strict_types=1);

namespace Rakitan\Lib\Aura;

use Tracy\IBarPanel;
use Psr\Log\AbstractLogger;

/**
 * menampilkan log database pada panel tracy
 * @author arifk
 *
 */
class TracyAuraSql extends AbstractLogger implements IBarPanel
{
    protected $messages = [];
    protected $queryCount = 0;
    protected $totalTime = 0;
    protected $connectionName;

    public function __construct($connectionName)
    {
        $this->connectionName = $connectionName;
    }

    public function log($level, $message, array $context = [])
    {
        $replace = [];
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
            if ($key == 'duration') {
                $this->totalTime += $val;
            }
        }
        $tmp = strtr($message, $replace);
        list($time, $function, $stmt, $params) = explode('|', $tmp);
        $this->messages[] = [
            'time' => $time*1000,
            'function' => $function,
            'sql' => $stmt,
            'params' => $params,
        ];
        $this->queryCount++;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function getTab()
    {
        return '<span title="DB">'
                . '<img width="16" height="16" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAEYSURBVBgZBcHPio5hGAfg6/2+R980k6wmJgsJ5U/ZOAqbSc2GnXOwUg7BESgLUeIQ1GSjLFnMwsKGGg1qxJRmPM97/1zXFAAAAEADdlfZzr26miup2svnelq7d2aYgt3rebl585wN6+K3I1/9fJe7O/uIePP2SypJkiRJ0vMhr55FLCA3zgIAOK9uQ4MS361ZOSX+OrTvkgINSjS/HIvhjxNNFGgQsbSmabohKDNoUGLohsls6BaiQIMSs2FYmnXdUsygQYmumy3Nhi6igwalDEOJEjPKP7CA2aFNK8Bkyy3fdNCg7r9/fW3jgpVJbDmy5+PB2IYp4MXFelQ7izPrhkPHB+P5/PjhD5gCgCenx+VR/dODEwD+A3T7nqbxwf1HAAAAAElFTkSuQmCC" />'
                . '&nbsp;' . $this->queryCount . ' | ' . round($this->totalTime*1000, 2) . 'ms'
                . '</span>';
    }

    public function getPanel()
    {
        ob_start(function(){});
        $messages = $this->messages;
        require __DIR__ . '/aurasql-panel.php';
        return ob_get_clean();
    }
}
