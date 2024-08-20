<script language="javascript" type="text/javascript">
// <![CDATA[
function checkNum(x) {
    if (!(/^\d+\d{9}$/.test(x.value))) {
        alert("Only 10 Digits Numeric Values Allowed");
        x.focus();
        return false;
    }
    return true;
}
// ]]>
</script>
<script type = "text/javascript" >
// <![CDATA[
$(window).load(function () {
    $("#tm_field").keyup(function () {
        el = $(this);
        if (el.val().length >= 160) {
            el.val(el.val().substr(0, 160));
        } else {
            $("#charNum").text(160 - el.val().length);
        }
    });
});
// ]]>
</script>
</p>
<p>
    <title>Test Page</title>
</p>
<p><strong>This Free SMS page is still under construction,</strong></p>
<p><strong>M working on it &amp; it will start soon ................</strong></p>
<form action="process.php" method="post">
    <table border="1" align="center">
        <tbody>
            <tr>
                <td><span data-mce-mark="1"><span color="red" style="color: red;" data-mce-mark="1">*</span> Mobile Number </span></td>
                <td>
                    <p><input type="text" name="NUM" id="mn_field" onchange="checkNum(this)" /></p>
                    <p><small> Please enter 10 digits mobile number, only.</small></p>
                </td>
            </tr>
            <tr>
                <td><span data-mce-mark="1"><span color="red" style="color: red;" data-mce-mark="1">*</span> Text Message </span></td>
                <td align="center"><textarea id="tm_field" rows="7" cols="25"></textarea></td>
            </tr>
            <tr>
                <td colspan="1" align="center">Character remain(s)</td>
                <td id="charNum">160</td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input type="submit" value="Bye Bye SMS" /></td>
            </tr>
            <tr>
                <td colspan="2" bgcolor="#ffffcc" align="center"><small><span style="text-decoration: underline;" data-mce-mark="1">N.B.</span><strong> </strong>:- A " <span color="red" style="color: red;" data-mce-mark="1">*</span> " indicates a field is required</small></td>
            </