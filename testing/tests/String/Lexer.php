<?php

namespace SpencerMortensen\Parser\String;


// Test
$lexer = new Lexer($subject);
$isMatch = $lexer->getString($target);

// Input
$subject = '';
$target = '';

// Output
$isMatch = true;

// Input
$subject = '';
$target = 'a';

// Output
$isMatch = false;

// Input
$subject = 'a';
$target = '';

// Output
$isMatch = true;

// Input
$subject = 'a';
$target = 'a';

// Output
$isMatch = true;

// Input
$subject = 'a';
$target = 'b';

// Output
$isMatch = false;

// Input
$subject = 'abc';
$target = 'abc';

// Output
$isMatch = true;

// Input
$subject = 'abc';
$target = 'abd';

// Output
$isMatch = false;


// Test
$lexer = new Lexer($subject);
$isMatch = $lexer->getRe($target);

// Input
$subject = '';
$target = '';

// Output
$isMatch = true;

// Input
$subject = '';
$target = 'a';

// Output
$isMatch = false;

// Input
$subject = '';
$target = 'a?';

// Output
$isMatch = true;

// Input
$subject = 'a';
$target = '';

// Output
$isMatch = true;

// Input
$subject = 'a';
$target = 'a';

// Output
$isMatch = true;

// Input
$subject = 'a';
$target = 'b';

// Output
$isMatch = false;

// Input
$subject = 'abc';
$target = 'abd';

// Output
$isMatch = false;

// Input
$subject = 'abc';
$target = 'ab(c|d)';

// Output
$isMatch = true;


// Test
$lexer = new Lexer($subject);
$lexer->getString($target);
$position = $lexer->getPosition();
$isHalted = $lexer->isHalted();

// Input
$subject = '';
$target = '';

// Output
$position = 0;
$isHalted = true;

// Input
$subject = 'a';
$target = '';

// Output
$position = 0;
$isHalted = false;

// Input
$subject = 'a';
$target = 'a';

// Output
$position = 1;
$isHalted = true;

// Input
$subject = 'ab';
$target = 'a';

// Output
$position = 1;
$isHalted = false;

// Input
$subject = 'ab';
$target = 'ab';

// Output
$position = 2;
$isHalted = true;
