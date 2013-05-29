{* Smarty *}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{if $title}{$title}{else}LAF Intranet{/if}</title>
    <meta http-equiv="pragma" content="no-cache"></meta>

    <!-- TODO
    <link rel="icon" href="/i/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="/i/favicon.ico" type="image/x-icon" />
    -->

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <link rel="stylesheet" href="/css/bootstrap.css">
    <link rel="stylesheet" href="/css/bootstrap-responsive.css">
    <script src="/js/jquery-1.9.1.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/bootstrap-datepicker.js"></script>

</head>
<body>
    {include "Blocks/Header.tpl"}
    <div class="container">
        <div class="row-fluid">
            {include "Blocks/Errors.tpl"}
            {$__block_main}
        </div>
    </div>

    {include "Blocks/Footer.tpl"}
</body>
</html>
