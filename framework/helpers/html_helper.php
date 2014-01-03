<?php

/**
*
*/
class HtmlHelper
{
    public function __construct()
    {
        # code...
    }

    public function createLink($title, $url, $htmlAttrs=array())
    {
        $link = "<a href='$url'";
        foreach ($htmlAttrs as $key => $value) {
            $link .= " $key='$value'";
        }
        $link .= ">$title</a>";

        return $link;
    }

    public function url($controller,$action='index', $params=array())
    {
        return Dispatcher::url($controller,$action, $params);
    }

    public function table($data, $tableClasses=array())
    {
        $tableTags=array('table','thead','tfoot','tr','td');
        foreach ($tableTags as $value) {
            $tableClass[$value] = "";
            if (isset($tableClasses[$value])) {
                $tableClass[$value] = "class=\"" . $tableClasses[$value] . "\"";
            }
        }
        ?>
        <table <?php echo $tableClass['table']; ?>>
            <thead <?php echo $tableClass['thead']; ?>>
                <tr>
        <?php
        reset($data);
        $currEl = current($data);
        foreach ($currEl as $key => $value) {
            if (is_array($value)) {
                    continue;
            }
            echo "<th>$key</th>\n";
        }
        ?>
                </tr>
            </thead>
        <tbody>
        <?php
        foreach ($data as $row) {
            echo "<tr " . $tableClass['tr'] . ">";
            foreach ($row as $key => $value) {
                if (is_array($value)) {
                    continue;
                }
                echo "<td " . $tableClass['td'] . ">$value</td>\n";
            }
            echo "</tr>\n";
        }
        ?>
            </tbody>
        </table>
    <?php
    }

}
