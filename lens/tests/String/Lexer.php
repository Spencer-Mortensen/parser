<?php

namespace SpencerMortensen\Parser\String;


// Test
$lexer = new Lexer($subject);
$isMatch = $lexer->getString($target);

// Cause
$subject = '';
$target = '';

// Effect
$isMatch = true;

// Cause
$subject = '';
$target = 'a';

// Effect
$isMatch = false;

// Cause
$subject = 'a';
$target = '';

// Effect
$isMatch = true;

// Cause
$subject = 'a';
$target = 'a';

// Effect
$isMatch = true;

// Cause
$subject = 'a';
$target = 'b';

// Effect
$isMatch = false;

// Cause
$subject = 'abc';
$target = 'abc';

// Effect
$isMatch = true;

// Cause
$subject = 'abc';
$target = 'abd';

// Effect
$isMatch = false;


// Test
$lexer = new Lexer($subject);
$isMatch = $lexer->getRe($target);

// Cause
$subject = '';
$target = '';

// Effect
$isMatch = true;

// Cause
$subject = '';
$target = 'a';

// Effect
$isMatch = false;

// Cause
$subject = '';
$target = 'a?';

// Effect
$isMatch = true;

// Cause
$subject = 'a';
$target = '';

// Effect
$isMatch = true;

// Cause
$subject = 'a';
$target = 'a';

// Effect
$isMatch = true;

// Cause
$subject = 'a';
$target = 'b';

// Effect
$isMatch = false;

// Cause
$subject = 'abc';
$target = 'abd';

// Effect
$isMatch = false;

// Cause
$subject = 'abc';
$target = 'ab(c|d)';

// Effect
$isMatch = true;


// Test
$lexer = new Lexer($subject);
$lexer->getString($target);
$position = $lexer->getPosition();
$isHalted = $lexer->isHalted();

// Cause
$subject = '';
$target = '';

// Effect
$position = 0;
$isHalted = true;

// Cause
$subject = 'a';
$target = '';

// Effect
$position = 0;
$isHalted = false;

// Cause
$subject = 'a';
$target = 'a';

// Effect
$position = 1;
$isHalted = true;

// Cause
$subject = 'ab';
$target = 'a';

// Effect
$position = 1;
$isHalted = false;

// Cause
$subject = 'ab';
$target = 'ab';

// Effect
$position = 2;
$isHalted = true;
