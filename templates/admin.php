<?php require 'header.php'; ?>

<h3>Admin Page</h3>
<div class='well'>
<?php if (!$isAdmin): ?>
    <form action='<?php echo urlFor("post_admin"); ?>' method='POST'>
        <p>Security code</p>
        <input type='hidden' name='cmd' value='login'>
        <input type='password' name='seccode'>
        <?php require 'recaptcha.php'; ?>
        <input type='submit' class='hidead btn btn-success buttonmargin input-block-level' value='Login'>
    </form>
<?php else: ?>
    <form action='<?php echo urlFor("post_admin"); ?>' method='POST' class='help-inline'>
        <input type='hidden' name='cmd' value='updatebalance'>
        <input type='submit' class='hidead btn btn-primary buttonmargin input-block-level' value='Update server balance'>
    </form>
    <form action='<?php echo urlFor("post_admin"); ?>' method='POST' class='help-inline'>
        <input type='hidden' name='cmd' value='logout'>
        <input type='submit' class='hidead btn btn-success buttonmargin input-block-level' value='Logout'>
    </form>
    <br>
    <b>Server Balance:</b> <?php echo $serverbalance; ?> <?php echo SUB_UNIT_NAME; ?>
    <ul id="tabs" class="nav nav-pills" data-tabs="tabs">
        <li class="active"><a data-target="#home" data-toggle="tab">Home</a></li>
    </ul>
<?php endif; ?>
</div>

<?php if ($isAdmin): ?>
<div class='well'>

    <div id="my-tab-content" class="tab-content">
        <div class="tab-pane active" id="home">
            <h4>Home</h4>
            <div class="span6">
                <table class="well table table-striped">
                    <caption>Balances</caption>
                    <tbody><tr>
                        <td>Number of addresses</td>
                        <td><span id="summary-addresses"><?php echo $statBalance["num_addresses"];?></span></td>
                    </tr>
                    <tr>
                        <td>Max of balances</td>
                        <td><span id="summary-max-balance"><?php printf("%s %s",  number_format($statBalance["max_balance"]), SUB_UNIT_NAME); ?></span></td>
                    </tr>
                    <tr>
                        <td>Sum of balances</td>
                        <td><span id="summary-sum-balance"><?php printf("%s %s",  number_format($statBalance["sum_balance"]), SUB_UNIT_NAME); ?></span></td>
                    </tr>
                    <tr>
                        <td>Max of Total balances</td>
                        <td><span id="summary-max-total-balance"><?php printf("%s %s",  number_format($statBalance["max_totalbalance"]), SUB_UNIT_NAME); ?></span></td>
                    </tr>
                    <tr>
                        <td>Sum of Total balances</td>
                        <td><span id="summary-sum-total-balance"><?php printf("%s %s",  number_format($statBalance["sum_totalbalance"]), SUB_UNIT_NAME); ?></span></td>
                    </tr>
                </tbody></table>
            </div>
        </div>
    </div>

</div>
<?php endif; ?>

<script>
$('a[data-toggle="tab"]').on('shown', function (e) {
    var contentID = $(e.target).attr("data-target");
    var contentURL = $(e.target).attr("href");
    if (typeof(contentURL) != 'undefined') {
        // state: has a url to load from
        $(contentID).load('<?php echo urlFor("post_admin"); ?>/'+contentURL, function(){
            $('#myTab').tab(); //reinitialize tabs
        });
    }
})
</script>

<?php require 'footer.php'; ?>