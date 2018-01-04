<?php
require 'FormHelper.php';

$sweets = array(
    'puff' => '참깨 버프',
    'square' => '코코넛 우유 젤리',
    'cake' => '흑설탕 케이크',
    'ricmeat' => '찹쌀 경단'
);

$main_dishs = array(
    'cuke' => '데친 해삼',
    'stomach' => '순대',
    'triple' => '와인 소스 양대창',
    'taro' => '돼지고기 토란국',
    'giblerts' => '곱창 소금 구이',
    'abalone' => '전복 호박 볶음'
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    list($errors, $input) = validate_form();

    if ($errors) {
        show_form($errors);
    } else {
        process_form($input);
    }
} else {
    show_form();
}

function show_form($errors = array())
{
    $defaults = array(
        'delivery' => 'yes',
        'size' => 'medium'
    );

    $form = new FormHelper($defaults);
    include 'complete-form.php';
}

function validate_form()
{
    $input = array();
    $errors = array();

    $input['name'] = filter_input(INPUT_POST, 'name');
    if (! strlen($input['name'])) {
        $errors[] = '이름을 입력해주세요.';
    }

    $input['size'] = filter_input(INPUT_POST, 'size');
    if (! in_array($input['size'], ['small', 'medium', 'large'])) {
        $errors[] = '크기를 선택해주세요.';
    }

    $input['sweet'] = filter_input(INPUT_POST, 'sweet');
    if (! array_key_exists($input['sweet'], $GLOBALS['sweets'])) {
        $errors[] = '디저트를 선택해주세요.';
    }

    $input['main_dish'] = filter_input(INPUT_POST, 'main_dish', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

    if (count($input['main_dish']) !== 2) {
        $errors[] = '주 요리를 두가지 선택해주세요.';
    }

    if (! (array_key_exists($input['main_dish'][0], $GLOBALS['main_dishs']) &&
        array_key_exists($input['main_dish'][1], $GLOBALS['main_dishs']))) {
        $errors[] = '주 요리를 두가지 선택해주세요..';
    }

    $input['delivery'] = filter_input(INPUT_POST, 'delivery');
    $input['comments'] = filter_input(INPUT_POST, 'comments');

    if ($input['delivery'] === 'yes' && (! strlen(trim($input['comments'])))) {
        $errors[] = '배달 주소를 입력해주세요.';
    }

    return array($errors, $input);
}

function process_form($input)
{
    $sweet = $GLOBALS['sweets'][$input['sweet']];
    $main_dish1 = $GLOBALS['main_dishs'][$input['main_dish'][0]];
    $main_dish2 = $GLOBALS['main_dishs'][$input['main_dish'][1]];
    if (isset($input['delivery']) && $input['delivery'] === 'yes') {
        $delivery = '배달';
    } else {
        $delivery = '매장 방문';
    }

    $message = <<<_ORDER_
    주문이 완료되었습니다, {$input['name']} 님.
    $sweet({$input['size']}), $main_dish1, $main_dish2 를 주문했습니다.
    배달여부 : $delivery
_ORDER_;

    if (strlen(trim($input['comments']))) {
        $message .= ' / 메모 : ' . $input['comments'];
    }

    // mail('sue@emato.net', 'New Order', $message);

    print nl2br(htmlentities($message));

}
