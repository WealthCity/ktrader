<?php
    echo '<div align="center">';
    
    for ($i = 65; $i < 91; $i++)
    {
        if($letter == chr($i))
        {
            echo chr($i).'   ';
        }
        else
        {
            echo '<a href="stock_list.php?l='.chr($i).'&cap='.$cap.'&price='.$price.'&eps='.$eps.'&ppe='.$ppe.'"><u>'.chr($i).'</u></a>   ';
        }
    }
    echo '</div><br /><br />';
    echo $stockTable;
?>