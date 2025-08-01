import { type SharedData } from '@/types';
import { Head, Link, usePage, router } from '@inertiajs/react';

interface GameData {
    wins: number;
    losses: number;
    ties: number;
    userChoice: string | null;
    computerChoice: string | null;
    lastResult: string | null;
    [key: string]: unknown;
}

interface WelcomeProps extends SharedData {
    gameData?: GameData;
    [key: string]: unknown;
}

export default function Welcome() {
    const { auth, gameData } = usePage<WelcomeProps>().props;

    const choices = [
        { value: 'rock', emoji: '🪨', label: 'Rock' },
        { value: 'paper', emoji: '📄', label: 'Paper' },
        { value: 'scissors', emoji: '✂️', label: 'Scissors' }
    ];

    const getChoiceEmoji = (choice: string | null) => {
        if (!choice) return '❓';
        const found = choices.find(c => c.value === choice);
        return found ? found.emoji : '❓';
    };

    const getResultMessage = (result: string | null) => {
        if (!result) return '';
        if (result === 'win') return '🎉 You Won!';
        if (result === 'lose') return '😢 You Lost!';
        return '🤝 It\'s a Tie!';
    };

    const getResultColor = (result: string | null) => {
        if (result === 'win') return 'text-green-600 dark:text-green-400';
        if (result === 'lose') return 'text-red-600 dark:text-red-400';
        return 'text-yellow-600 dark:text-yellow-400';
    };

    const handlePlay = (choice: string) => {
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

    return (
        <>
            <Head title="Paper-Rock-Scissors Game">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]">
                <header className="mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-4xl">
                    <nav className="flex items-center justify-end gap-4">
                        {auth.user ? (
                            <Link
                                href={route('dashboard')}
                                className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                            >
                                Dashboard
                            </Link>
                        ) : (
                            <>
                                <Link
                                    href={route('login')}
                                    className="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                                >
                                    Log in
                                </Link>
                                <Link
                                    href={route('register')}
                                    className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                                >
                                    Register
                                </Link>
                            </>
                        )}
                    </nav>
                </header>
                <div className="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0">
                    <main className="flex w-full max-w-[335px] flex-col-reverse lg:max-w-4xl lg:flex-row">
                        <div className="flex-1 rounded-br-lg rounded-bl-lg bg-white p-6 pb-12 text-center shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] lg:rounded-tl-lg lg:rounded-br-none lg:p-20 dark:bg-[#161615] dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]">
                            <h1 className="mb-4 text-4xl font-bold">🪨📄✂️ Paper-Rock-Scissors</h1>
                            <p className="mb-8 text-lg text-[#706f6c] dark:text-[#A1A09A]">
                                Challenge the computer and test your luck! 🎮
                            </p>

                            {/* Score Board */}
                            <div className="mb-8 rounded-lg bg-gray-50 p-6 dark:bg-[#1a1a19]">
                                <h2 className="mb-4 text-xl font-semibold">📊 Score Board</h2>
                                <div className="grid grid-cols-3 gap-4 text-center">
                                    <div className="rounded-md bg-green-100 p-3 dark:bg-green-900/30">
                                        <div className="text-2xl font-bold text-green-600 dark:text-green-400">
                                            {gameData?.wins || 0}
                                        </div>
                                        <div className="text-sm text-green-700 dark:text-green-300">Wins 🏆</div>
                                    </div>
                                    <div className="rounded-md bg-red-100 p-3 dark:bg-red-900/30">
                                        <div className="text-2xl font-bold text-red-600 dark:text-red-400">
                                            {gameData?.losses || 0}
                                        </div>
                                        <div className="text-sm text-red-700 dark:text-red-300">Losses 😔</div>
                                    </div>
                                    <div className="rounded-md bg-yellow-100 p-3 dark:bg-yellow-900/30">
                                        <div className="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                                            {gameData?.ties || 0}
                                        </div>
                                        <div className="text-sm text-yellow-700 dark:text-yellow-300">Ties 🤝</div>
                                    </div>
                                </div>
                            </div>

                            {/* Last Round Result */}
                            {gameData?.lastResult && (
                                <div className="mb-8 rounded-lg bg-gray-50 p-6 dark:bg-[#1a1a19]">
                                    <h3 className="mb-4 text-lg font-semibold">🎯 Last Round</h3>
                                    <div className="mb-4 flex items-center justify-center gap-8">
                                        <div className="text-center">
                                            <div className="text-4xl mb-2">
                                                {getChoiceEmoji(gameData.userChoice)}
                                            </div>
                                            <div className="text-sm font-medium">You</div>
                                        </div>
                                        <div className="text-2xl">⚔️</div>
                                        <div className="text-center">
                                            <div className="text-4xl mb-2">
                                                {getChoiceEmoji(gameData.computerChoice)}
                                            </div>
                                            <div className="text-sm font-medium">Computer</div>
                                        </div>
                                    </div>
                                    <div className={`text-xl font-bold ${getResultColor(gameData.lastResult)}`}>
                                        {getResultMessage(gameData.lastResult)}
                                    </div>
                                </div>
                            )}

                            {/* Game Buttons */}
                            <div className="mb-8">
                                <h3 className="mb-4 text-lg font-semibold">🎮 Make Your Choice</h3>
                                <div className="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                    {choices.map((choice) => (
                                        <button
                                            key={choice.value}
                                            onClick={() => handlePlay(choice.value)}
                                            className="flex flex-col items-center gap-2 rounded-lg border-2 border-[#19140035] bg-white p-6 text-lg font-medium transition-all hover:border-[#1915014a] hover:bg-gray-50 dark:border-[#3E3E3A] dark:bg-[#161615] dark:hover:border-[#62605b] dark:hover:bg-[#1a1a19]"
                                        >
                                            <div className="text-4xl">{choice.emoji}</div>
                                            <div>{choice.label}</div>
                                        </button>
                                    ))}
                                </div>
                            </div>

                            {/* Reset Button */}
                            {(gameData?.wins || gameData?.losses || gameData?.ties) ? (
                                <button
                                    onClick={handleReset}
                                    className="rounded-lg border border-red-300 bg-red-50 px-6 py-2 text-red-700 transition-colors hover:bg-red-100 dark:border-red-700 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50"
                                >
                                    🔄 Reset Game
                                </button>
                            ) : null}

                            <footer className="mt-12 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                Built with ❤️ by{" "}
                                <a 
                                    href="https://app.build" 
                                    target="_blank" 
                                    className="font-medium text-[#f53003] hover:underline dark:text-[#FF4433]"
                                >
                                    app.build
                                </a>
                            </footer>
                        </div>
                    </main>
                </div>
                <div className="hidden h-14.5 lg:block"></div>
            </div>
        </>
    );
}