import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    return (
        <>
            <Head title="School Attendance System">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="flex min-h-screen flex-col items-center bg-gradient-to-br from-blue-50 via-white to-indigo-50 p-6 text-gray-800 lg:justify-center lg:p-8 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 dark:text-gray-200">
                <header className="mb-8 w-full max-w-6xl">
                    <nav className="flex items-center justify-between">
                        <div className="flex items-center space-x-2">
                            <div className="text-3xl">🎓</div>
                            <h1 className="text-xl font-semibold">EduTrack</h1>
                        </div>
                        <div className="flex items-center gap-4">
                            {auth.user ? (
                                <Link
                                    href={route('dashboard')}
                                    className="inline-block rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition-colors"
                                >
                                    Go to Dashboard
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href={route('login')}
                                        className="inline-block rounded-lg border border-gray-300 px-5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                                    >
                                        Log in
                                    </Link>
                                    <Link
                                        href={route('register')}
                                        className="inline-block rounded-lg bg-blue-600 px-5 py-2 text-sm font-medium text-white hover:bg-blue-700 transition-colors"
                                    >
                                        Register
                                    </Link>
                                </>
                            )}
                        </div>
                    </nav>
                </header>

                <main className="w-full max-w-6xl">
                    {/* Hero Section */}
                    <div className="text-center mb-16">
                        <div className="mb-6">
                            <div className="text-8xl mb-4">📚</div>
                            <h1 className="text-5xl font-bold mb-4 bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                                School Attendance System
                            </h1>
                            <p className="text-xl text-gray-600 mb-8 dark:text-gray-400">
                                Streamline student attendance tracking with powerful management tools for educators and administrators
                            </p>
                        </div>
                        
                        {!auth.user && (
                            <div className="flex justify-center gap-4 mb-12">
                                <Link
                                    href={route('register')}
                                    className="inline-block rounded-lg bg-blue-600 px-8 py-3 text-lg font-medium text-white hover:bg-blue-700 transition-colors shadow-lg"
                                >
                                    Get Started Free 🚀
                                </Link>
                                <Link
                                    href={route('login')}
                                    className="inline-block rounded-lg border-2 border-blue-600 px-8 py-3 text-lg font-medium text-blue-600 hover:bg-blue-50 transition-colors dark:hover:bg-blue-900/20"
                                >
                                    Sign In
                                </Link>
                            </div>
                        )}
                    </div>

                    {/* Features Grid */}
                    <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                        <div className="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow dark:bg-gray-800">
                            <div className="text-4xl mb-4">👥</div>
                            <h3 className="text-xl font-semibold mb-2">Student Management</h3>
                            <p className="text-gray-600 dark:text-gray-400">
                                Register and manage student information, enrollment, and class assignments efficiently.
                            </p>
                        </div>

                        <div className="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow dark:bg-gray-800">
                            <div className="text-4xl mb-4">🏫</div>
                            <h3 className="text-xl font-semibold mb-2">Class Organization</h3>
                            <p className="text-gray-600 dark:text-gray-400">
                                Create and manage classes, assign teachers, and track class capacity and status.
                            </p>
                        </div>

                        <div className="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow dark:bg-gray-800">
                            <div className="text-4xl mb-4">✅</div>
                            <h3 className="text-xl font-semibold mb-2">Smart Attendance</h3>
                            <p className="text-gray-600 dark:text-gray-400">
                                Mark attendance quickly with status tracking: present, absent, late, or excused.
                            </p>
                        </div>

                        <div className="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow dark:bg-gray-800">
                            <div className="text-4xl mb-4">📊</div>
                            <h3 className="text-xl font-semibold mb-2">Detailed Reports</h3>
                            <p className="text-gray-600 dark:text-gray-400">
                                Generate comprehensive attendance reports and analytics for informed decisions.
                            </p>
                        </div>
                    </div>

                    {/* Role-based Access */}
                    <div className="bg-white rounded-2xl p-8 shadow-xl mb-16 dark:bg-gray-800">
                        <h2 className="text-3xl font-bold text-center mb-8">
                            Built for Different Roles 🎯
                        </h2>
                        <div className="grid md:grid-cols-2 gap-8">
                            <div className="text-center">
                                <div className="text-6xl mb-4">👨‍💼</div>
                                <h3 className="text-2xl font-semibold mb-4 text-blue-600">Administrators</h3>
                                <ul className="text-left space-y-2 text-gray-600 dark:text-gray-400">
                                    <li className="flex items-center"><span className="text-green-500 mr-2">✓</span> Manage all students and teachers</li>
                                    <li className="flex items-center"><span className="text-green-500 mr-2">✓</span> Create and organize classes</li>
                                    <li className="flex items-center"><span className="text-green-500 mr-2">✓</span> View comprehensive reports</li>
                                    <li className="flex items-center"><span className="text-green-500 mr-2">✓</span> Monitor attendance trends</li>
                                    <li className="flex items-center"><span className="text-green-500 mr-2">✓</span> Full system oversight</li>
                                </ul>
                            </div>
                            <div className="text-center">
                                <div className="text-6xl mb-4">👩‍🏫</div>
                                <h3 className="text-2xl font-semibold mb-4 text-green-600">Teachers</h3>
                                <ul className="text-left space-y-2 text-gray-600 dark:text-gray-400">
                                    <li className="flex items-center"><span className="text-green-500 mr-2">✓</span> Mark attendance for their classes</li>
                                    <li className="flex items-center"><span className="text-green-500 mr-2">✓</span> View student attendance history</li>
                                    <li className="flex items-center"><span className="text-green-500 mr-2">✓</span> Add attendance notes</li>
                                    <li className="flex items-center"><span className="text-green-500 mr-2">✓</span> Generate class reports</li>
                                    <li className="flex items-center"><span className="text-green-500 mr-2">✓</span> Track student progress</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {/* Demo Section */}
                    <div className="text-center bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-8 text-white mb-16">
                        <h2 className="text-3xl font-bold mb-4">
                            Ready to Transform Your School's Attendance? 🌟
                        </h2>
                        <p className="text-xl mb-6 opacity-90">
                            Join thousands of schools already using our attendance management system
                        </p>
                        {!auth.user && (
                            <div className="flex justify-center gap-4">
                                <Link
                                    href={route('register')}
                                    className="inline-block rounded-lg bg-white px-8 py-3 text-lg font-medium text-blue-600 hover:bg-gray-100 transition-colors"
                                >
                                    Start Free Trial
                                </Link>
                                <Link
                                    href={route('login')}
                                    className="inline-block rounded-lg border-2 border-white px-8 py-3 text-lg font-medium text-white hover:bg-white/10 transition-colors"
                                >
                                    Demo Login
                                </Link>
                            </div>
                        )}
                    </div>

                    {/* Features List */}
                    <div className="grid md:grid-cols-3 gap-6 mb-16">
                        <div className="flex items-start space-x-3">
                            <div className="text-2xl">⚡</div>
                            <div>
                                <h4 className="font-semibold mb-1">Lightning Fast</h4>
                                <p className="text-sm text-gray-600 dark:text-gray-400">Mark attendance for entire classes in seconds</p>
                            </div>
                        </div>
                        <div className="flex items-start space-x-3">
                            <div className="text-2xl">🔒</div>
                            <div>
                                <h4 className="font-semibold mb-1">Secure & Private</h4>
                                <p className="text-sm text-gray-600 dark:text-gray-400">Student data protected with enterprise-grade security</p>
                            </div>
                        </div>
                        <div className="flex items-start space-x-3">
                            <div className="text-2xl">📱</div>
                            <div>
                                <h4 className="font-semibold mb-1">Mobile Friendly</h4>
                                <p className="text-sm text-gray-600 dark:text-gray-400">Access from any device, anywhere, anytime</p>
                            </div>
                        </div>
                    </div>

                    <footer className="text-center text-sm text-gray-500 dark:text-gray-400">
                        <p>
                            Built with ❤️ using Laravel & React for modern school management
                        </p>
                    </footer>
                </main>
            </div>
        </>
    );
}