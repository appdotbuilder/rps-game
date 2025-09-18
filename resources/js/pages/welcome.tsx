import React from 'react';
import { type SharedData } from '@/types';
import { Head, Link, usePage, router } from '@inertiajs/react';

interface GameStats {
    human_wins: number;
    computer_wins: number;
    ties: number;
    total_games: number;
}

interface LastGame {
    human_choice: string;
    computer_choice: string;
    result: string;
    message: string;
}

interface Props {
    stats: GameStats;
    lastGame: LastGame | null;
    [key: string]: unknown;
}

export default function Welcome({ stats, lastGame }: Props) {
    const { auth } = usePage<SharedData>().props;

    const getChoiceEmoji = (choice: string) => {
        const emojis = {
            rock: '🪨',
            paper: '📄',
            scissors: '✂️',
        };
        return emojis[choice as keyof typeof emojis] || '❓';
    };

    const handleChoice = (choice: string) => {
        router.post(route('game.play'), { choice }, {
            preserveState: true,
            preserveScroll: true
        });
    };

    const handleReset = () => {
        router.delete(route('game.reset'), {
            preserveState: true,
            preserveScroll: true
        });
    };

    const getWinPercentage = () => {
        if (stats.total_games === 0) return 0;
        return Math.round((stats.human_wins / stats.total_games) * 100);
    };

    return (
        <>
            <Head title="Rock Paper Scissors">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="flex min-h-screen flex-col items-center bg-gradient-to-br from-blue-50 to-purple-50 p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:from-gray-900 dark:to-gray-800">
                <header className="mb-6 w-full max-w-[800px] text-sm">
                    <nav className="flex items-center justify-end gap-4">
                        {auth.user ? (
                            <Link
                                href={route('dashboard')}
                                className="inline-block rounded-lg border border-blue-200 px-5 py-1.5 text-sm leading-normal text-blue-700 hover:border-blue-300 hover:bg-blue-50 dark:border-blue-700 dark:text-blue-300 dark:hover:border-blue-600 dark:hover:bg-blue-900/20"
                            >
                                Dashboard
                            </Link>
                        ) : (
                            <>
                                <Link
                                    href={route('login')}
                                    className="inline-block rounded-lg border border-transparent px-5 py-1.5 text-sm leading-normal text-gray-700 hover:bg-white/50 dark:text-gray-300 dark:hover:bg-white/5"
                                >
                                    Log in
                                </Link>
                                <Link
                                    href={route('register')}
                                    className="inline-block rounded-lg border border-blue-200 px-5 py-1.5 text-sm leading-normal text-blue-700 hover:border-blue-300 hover:bg-blue-50 dark:border-blue-700 dark:text-blue-300 dark:hover:border-blue-600 dark:hover:bg-blue-900/20"
                                >
                                    Register
                                </Link>
                            </>
                        )}
                    </nav>
                </header>

                <div className="w-full max-w-4xl">
                    <main className="rounded-xl bg-white/80 backdrop-blur-sm p-8 shadow-xl dark:bg-gray-900/80">
                        {/* Header */}
                        <div className="text-center mb-8">
                            <h1 className="text-4xl font-bold text-gray-900 mb-2 dark:text-white">
                                🪨📄✂️ Rock Paper Scissors
                            </h1>
                            <p className="text-lg text-gray-600 dark:text-gray-300">
                                Play against the computer and test your luck!
                            </p>
                        </div>

                        {/* Game Stats */}
                        <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                            <div className="bg-green-100 rounded-lg p-4 text-center dark:bg-green-900/30">
                                <div className="text-2xl font-bold text-green-700 dark:text-green-300">
                                    {stats.human_wins}
                                </div>
                                <div className="text-sm text-green-600 dark:text-green-400">
                                    Your Wins
                                </div>
                            </div>
                            <div className="bg-red-100 rounded-lg p-4 text-center dark:bg-red-900/30">
                                <div className="text-2xl font-bold text-red-700 dark:text-red-300">
                                    {stats.computer_wins}
                                </div>
                                <div className="text-sm text-red-600 dark:text-red-400">
                                    Computer Wins
                                </div>
                            </div>
                            <div className="bg-yellow-100 rounded-lg p-4 text-center dark:bg-yellow-900/30">
                                <div className="text-2xl font-bold text-yellow-700 dark:text-yellow-300">
                                    {stats.ties}
                                </div>
                                <div className="text-sm text-yellow-600 dark:text-yellow-400">
                                    Ties
                                </div>
                            </div>
                            <div className="bg-blue-100 rounded-lg p-4 text-center dark:bg-blue-900/30">
                                <div className="text-2xl font-bold text-blue-700 dark:text-blue-300">
                                    {getWinPercentage()}%
                                </div>
                                <div className="text-sm text-blue-600 dark:text-blue-400">
                                    Win Rate
                                </div>
                            </div>
                        </div>

                        {/* Last Game Result */}
                        {lastGame && (
                            <div className="mb-8 p-6 rounded-xl bg-gradient-to-r from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30">
                                <div className="text-center">
                                    <div className="flex justify-center items-center gap-8 mb-4">
                                        <div className="text-center">
                                            <div className="text-6xl mb-2">
                                                {getChoiceEmoji(lastGame.human_choice)}
                                            </div>
                                            <div className="text-sm font-medium text-gray-600 dark:text-gray-300">
                                                You chose {lastGame.human_choice}
                                            </div>
                                        </div>
                                        <div className="text-4xl text-gray-400">VS</div>
                                        <div className="text-center">
                                            <div className="text-6xl mb-2">
                                                {getChoiceEmoji(lastGame.computer_choice)}
                                            </div>
                                            <div className="text-sm font-medium text-gray-600 dark:text-gray-300">
                                                Computer chose {lastGame.computer_choice}
                                            </div>
                                        </div>
                                    </div>
                                    <div className="text-xl font-bold text-gray-900 dark:text-white">
                                        {lastGame.message}
                                    </div>
                                </div>
                            </div>
                        )}

                        {/* Game Controls */}
                        <div className="text-center mb-8">
                            <p className="text-lg text-gray-700 mb-6 dark:text-gray-300">
                                Choose your weapon:
                            </p>
                            <div className="flex justify-center gap-4 flex-wrap">
                                <button
                                    onClick={() => handleChoice('rock')}
                                    className="group relative overflow-hidden rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 p-8 shadow-lg transition-all duration-200 hover:scale-105 hover:shadow-xl dark:from-gray-700 dark:to-gray-800 dark:hover:from-gray-600 dark:hover:to-gray-700"
                                >
                                    <div className="text-6xl mb-3 group-hover:scale-110 transition-transform">
                                        🪨
                                    </div>
                                    <div className="text-xl font-bold text-gray-800 dark:text-gray-200">
                                        Rock
                                    </div>
                                </button>
                                <button
                                    onClick={() => handleChoice('paper')}
                                    className="group relative overflow-hidden rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 hover:from-blue-200 hover:to-blue-300 p-8 shadow-lg transition-all duration-200 hover:scale-105 hover:shadow-xl dark:from-blue-700 dark:to-blue-800 dark:hover:from-blue-600 dark:hover:to-blue-700"
                                >
                                    <div className="text-6xl mb-3 group-hover:scale-110 transition-transform">
                                        📄
                                    </div>
                                    <div className="text-xl font-bold text-blue-800 dark:text-blue-200">
                                        Paper
                                    </div>
                                </button>
                                <button
                                    onClick={() => handleChoice('scissors')}
                                    className="group relative overflow-hidden rounded-xl bg-gradient-to-br from-red-100 to-red-200 hover:from-red-200 hover:to-red-300 p-8 shadow-lg transition-all duration-200 hover:scale-105 hover:shadow-xl dark:from-red-700 dark:to-red-800 dark:hover:from-red-600 dark:hover:to-red-700"
                                >
                                    <div className="text-6xl mb-3 group-hover:scale-110 transition-transform">
                                        ✂️
                                    </div>
                                    <div className="text-xl font-bold text-red-800 dark:text-red-200">
                                        Scissors
                                    </div>
                                </button>
                            </div>
                        </div>

                        {/* Reset Button */}
                        {stats.total_games > 0 && (
                            <div className="text-center">
                                <button
                                    onClick={handleReset}
                                    className="px-6 py-3 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium transition-colors dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200"
                                >
                                    🔄 Reset Game
                                </button>
                            </div>
                        )}

                        {/* Game Rules */}
                        <div className="mt-12 p-6 bg-gray-50 rounded-xl dark:bg-gray-800/50">
                            <h3 className="text-lg font-bold text-gray-900 mb-4 text-center dark:text-white">
                                🎮 How to Play
                            </h3>
                            <div className="grid md:grid-cols-3 gap-4 text-center">
                                <div className="text-gray-600 dark:text-gray-300">
                                    <div className="text-2xl mb-2">🪨 beats ✂️</div>
                                    <div>Rock crushes Scissors</div>
                                </div>
                                <div className="text-gray-600 dark:text-gray-300">
                                    <div className="text-2xl mb-2">📄 beats 🪨</div>
                                    <div>Paper covers Rock</div>
                                </div>
                                <div className="text-gray-600 dark:text-gray-300">
                                    <div className="text-2xl mb-2">✂️ beats 📄</div>
                                    <div>Scissors cuts Paper</div>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </>
    );
}