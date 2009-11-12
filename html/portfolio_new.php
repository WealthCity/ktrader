<?php
    echo '
    <form action="portfolio.php?do=processForm" method="POST">
    <label>Portfolio Name</label>
    <input type="text" id="name" name="name"/>
    <label>Portfolio Description</label>
    <textarea cols="20" rows="4" id="description" name="description"></textarea>
    <label>Save Form</label>
    <input type="submit" value="Create Portfolio" />
        ';
?>