<?php

const INPUT= [
    '2',
    'X.O',
    'OO.',
    'XXX',
    '',
    'O.X',
    'XX.',
    'OOO',
    '',
    'XXX',
    'XOO',
    'XOO',
];

const WIN_POSITIONS = [
    [0, 1, 2],
    [3, 4, 5],
    [6, 7, 8],
    [0, 3, 6],
    [1, 4, 7],
    [2, 5, 8],
    [0, 4, 8],
    [2, 4, 6],
];

// read boards from INPUT array or from stdin
$inputBoards = extractInputBoards(INPUT);
//$inputBoards = manualInput();

// generate all reachable states
$reachableStates = [];
generateReachableStates(
    board: '.........',
    player: 'X',
    lines: WIN_POSITIONS,
    states: $reachableStates,
);

// our position is reachable state?
foreach ($inputBoards as $board) {
    echo isset($reachableStates[$board]) ? "yes\n" : "no\n";
}

function manualInput(): array
{
    $boardCount = (int) trim(fgets(STDIN));
    $boards = [];

    while (count($boards) < $boardCount) {
        $rows = [];

        while (count($rows) < 3) {
            $line = fgets(STDIN);

            if ($line === false) {
                throw new RuntimeException('Unexpected input');
            }

            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $rows[] = $line;
        }

        $boards[] = implode('', $rows);
    }

    return $boards;
}

function extractInputBoards(array $input): array
{
    $boardCount = (int) array_shift($input);

    if ($boardCount <= 0) {
        return [];
    }

    $boards = [];
    $rows = [];

    foreach ($input as $line) {
        if ($line === '') {
            continue;
        }

        $rows[] = $line;

        if (count($rows) === 3) {
            $boards[] = implode('', $rows);
            $rows = [];

            if (count($boards) === $boardCount) {
                break;
            }
        }
    }
    return $boards;
}

function hasWinner(string $board, array $positions): bool
{
    foreach ($positions as [$a, $b, $c]) {
        if ($board[$a] === '.') {
            continue;
        }

        if ($board[$a] === $board[$b] && $board[$a] === $board[$c]) {
            return true;
        }
    }

    return false;
}

function generateReachableStates(string $board, string $player, array $lines, array &$states): void
{
    if (isset($states[$board])) {
        return;
    }

    $states[$board] = true;

    if (hasWinner($board, $lines) || !str_contains($board, '.')) {
        return;
    }

    for ($i = 0; $i < 9; $i++) {
        if ($board[$i] !== '.') {
            continue;
        }

        $nextBoard = $board;
        $nextBoard[$i] = $player;

        generateReachableStates(
            board: $nextBoard,
            player: $player === 'X' ? 'O' : 'X',
            lines: $lines,
            states: $states,
        );
    }
}