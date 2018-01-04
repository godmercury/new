<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <title></title>
</head>
<body>
<form method="post" action="<?php echo $form->encode($_SERVER['PHP_SELF']); ?>">
<table>
    <?php if ($errors) { ?>
    <tr>
        <td>다음 항목을 수정해주세요.</td>
        <td>
            <ul>
                <?php foreach ($errors as $k => $v) { ?>
                <li><?php echo $form->encode($v); ?></li>
                <?php } ?>
            </ul>
        </td>
    </tr>
    <?php } ?>
    <tr>
        <td>이름 : </td>
        <td><?php echo $form->input('text', ['name' => 'name']); ?></td>
    </tr>
    <tr>
        <td>크기 : </td>
        <td>
            <?php
            echo $form->input('radio', ['name' => 'size', 'value' => 'small']) . '소';
            echo $form->input('radio', ['name' => 'size', 'value' => 'medium']) . '중';
            echo $form->input('radio', ['name' => 'size', 'value' => 'large']) . '대';
            ?>
        </td>
    </tr>
    <tr>
        <td>디저트를 선택해주세요. : </td>
        <td><?php echo $form->select($GLOBALS['sweets'], ['name' => 'sweet']); ?></td>
    </tr>
    <tr>
        <td>주 메뉴를 두가지 선택해주세요.</td>
        <td><?php echo $form->select($GLOBALS['main_dishs'], ['name' => 'main_dish', 'multiple' => true]); ?></td>
    </tr>
    <tr>
        <td>배달 주문이신가요?</td>
        <td><?php echo $form->input('checkbox', ['name' => 'delivery', 'value' => 'yes']) . '네'; ?></td>
    </tr>
    <tr>
        <td>전달사항 : </td>
        <td><?php echo $form->textarea(['name' => 'comments']); ?></td>
    </tr>
    <tr>
        <td colspan="2"><?php echo $form->input('submit', ['value' => '주문']); ?></td>
    </tr>
</table>
</form>

</body>
<html>
