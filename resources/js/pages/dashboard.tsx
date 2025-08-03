import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import { Users, School, CheckSquare, TrendingUp, Calendar, Clock, UserCheck, AlertCircle } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

interface Props {
    stats: {
        total_students?: number;
        total_classes?: number;
        total_teachers?: number;
        todays_attendance?: number;
        my_classes?: number;
        present_today?: number;
    };
    user_role: 'administrator' | 'teacher';
    recent_students?: Array<{
        id: number;
        student_id: string;
        first_name: string;
        last_name: string;
        school_class?: {
            name: string;
        };
    }>;
    recent_classes?: Array<{
        id: number;
        name: string;
        teacher?: {
            name: string;
        };
        students_count?: number;
        is_active: boolean;
    }>;
    [key: string]: unknown;
}

export default function Dashboard({ stats, user_role, recent_students, recent_classes }: Props) {
    const { auth } = usePage<SharedData>().props;

    const getGreeting = () => {
        const hour = new Date().getHours();
        if (hour < 12) return 'Good morning';
        if (hour < 17) return 'Good afternoon';
        return 'Good evening';
    };

    const formatUserRole = (role: string) => {
        return role === 'administrator' ? 'Administrator' : 'Teacher';
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="p-6 space-y-6">
                {/* Welcome Header */}
                <div className="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg p-6 text-white">
                    <div className="flex items-center justify-between">
                        <div>
                            <h1 className="text-2xl font-bold mb-2">
                                {getGreeting()}, {auth.user?.name}! 👋
                            </h1>
                            <p className="text-blue-100">
                                Welcome to your {formatUserRole(user_role)} dashboard. Here's what's happening today.
                            </p>
                        </div>
                        <div className="text-6xl opacity-20">
                            {user_role === 'administrator' ? '👨‍💼' : '👩‍🏫'}
                        </div>
                    </div>
                </div>

                {/* Quick Stats */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    {user_role === 'administrator' ? (
                        <>
                            <div className="bg-white rounded-lg p-6 shadow-sm border">
                                <div className="flex items-center justify-between">
                                    <div>
                                        <p className="text-sm font-medium text-gray-600">Total Students</p>
                                        <p className="text-3xl font-bold text-gray-900">{stats.total_students || 0}</p>
                                    </div>
                                    <div className="bg-blue-50 p-3 rounded-full">
                                        <Users className="h-6 w-6 text-blue-600" />
                                    </div>
                                </div>
                            </div>

                            <div className="bg-white rounded-lg p-6 shadow-sm border">
                                <div className="flex items-center justify-between">
                                    <div>
                                        <p className="text-sm font-medium text-gray-600">Active Classes</p>
                                        <p className="text-3xl font-bold text-gray-900">{stats.total_classes || 0}</p>
                                    </div>
                                    <div className="bg-green-50 p-3 rounded-full">
                                        <School className="h-6 w-6 text-green-600" />
                                    </div>
                                </div>
                            </div>

                            <div className="bg-white rounded-lg p-6 shadow-sm border">
                                <div className="flex items-center justify-between">
                                    <div>
                                        <p className="text-sm font-medium text-gray-600">Teachers</p>
                                        <p className="text-3xl font-bold text-gray-900">{stats.total_teachers || 0}</p>
                                    </div>
                                    <div className="bg-purple-50 p-3 rounded-full">
                                        <UserCheck className="h-6 w-6 text-purple-600" />
                                    </div>
                                </div>
                            </div>

                            <div className="bg-white rounded-lg p-6 shadow-sm border">
                                <div className="flex items-center justify-between">
                                    <div>
                                        <p className="text-sm font-medium text-gray-600">Today's Attendance</p>
                                        <p className="text-3xl font-bold text-gray-900">{stats.todays_attendance || 0}</p>
                                    </div>
                                    <div className="bg-orange-50 p-3 rounded-full">
                                        <CheckSquare className="h-6 w-6 text-orange-600" />
                                    </div>
                                </div>
                            </div>
                        </>
                    ) : (
                        <>
                            <div className="bg-white rounded-lg p-6 shadow-sm border">
                                <div className="flex items-center justify-between">
                                    <div>
                                        <p className="text-sm font-medium text-gray-600">My Classes</p>
                                        <p className="text-3xl font-bold text-gray-900">{stats.my_classes || 0}</p>
                                    </div>
                                    <div className="bg-blue-50 p-3 rounded-full">
                                        <School className="h-6 w-6 text-blue-600" />
                                    </div>
                                </div>
                            </div>

                            <div className="bg-white rounded-lg p-6 shadow-sm border">
                                <div className="flex items-center justify-between">
                                    <div>
                                        <p className="text-sm font-medium text-gray-600">My Students</p>
                                        <p className="text-3xl font-bold text-gray-900">{stats.total_students || 0}</p>
                                    </div>
                                    <div className="bg-green-50 p-3 rounded-full">
                                        <Users className="h-6 w-6 text-green-600" />
                                    </div>
                                </div>
                            </div>

                            <div className="bg-white rounded-lg p-6 shadow-sm border">
                                <div className="flex items-center justify-between">
                                    <div>
                                        <p className="text-sm font-medium text-gray-600">Present Today</p>
                                        <p className="text-3xl font-bold text-gray-900">{stats.present_today || 0}</p>
                                    </div>
                                    <div className="bg-purple-50 p-3 rounded-full">
                                        <UserCheck className="h-6 w-6 text-purple-600" />
                                    </div>
                                </div>
                            </div>

                            <div className="bg-white rounded-lg p-6 shadow-sm border">
                                <div className="flex items-center justify-between">
                                    <div>
                                        <p className="text-sm font-medium text-gray-600">Total Records</p>
                                        <p className="text-3xl font-bold text-gray-900">{stats.todays_attendance || 0}</p>
                                    </div>
                                    <div className="bg-orange-50 p-3 rounded-full">
                                        <CheckSquare className="h-6 w-6 text-orange-600" />
                                    </div>
                                </div>
                            </div>
                        </>
                    )}
                </div>

                {/* Quick Actions */}
                <div className="bg-white rounded-lg p-6 shadow-sm border">
                    <h2 className="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <TrendingUp className="h-5 w-5 mr-2 text-blue-600" />
                        Quick Actions
                    </h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <Link
                            href="/attendance/create"
                            className="flex items-center p-4 border-2 border-dashed border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors group"
                        >
                            <CheckSquare className="h-8 w-8 text-gray-400 group-hover:text-blue-600 mr-3" />
                            <div>
                                <p className="font-medium text-gray-900">Mark Attendance</p>
                                <p className="text-sm text-gray-500">Take today's attendance</p>
                            </div>
                        </Link>

                        {user_role === 'administrator' && (
                            <Link
                                href="/students/create"
                                className="flex items-center p-4 border-2 border-dashed border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition-colors group"
                            >
                                <Users className="h-8 w-8 text-gray-400 group-hover:text-green-600 mr-3" />
                                <div>
                                    <p className="font-medium text-gray-900">Add Student</p>
                                    <p className="text-sm text-gray-500">Register new student</p>
                                </div>
                            </Link>
                        )}

                        {user_role === 'administrator' && (
                            <Link
                                href="/classes/create"
                                className="flex items-center p-4 border-2 border-dashed border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-colors group"
                            >
                                <School className="h-8 w-8 text-gray-400 group-hover:text-purple-600 mr-3" />
                                <div>
                                    <p className="font-medium text-gray-900">Create Class</p>
                                    <p className="text-sm text-gray-500">Add new class</p>
                                </div>
                            </Link>
                        )}

                        <Link
                            href="/attendance"
                            className="flex items-center p-4 border-2 border-dashed border-gray-200 rounded-lg hover:border-orange-300 hover:bg-orange-50 transition-colors group"
                        >
                            <Calendar className="h-8 w-8 text-gray-400 group-hover:text-orange-600 mr-3" />
                            <div>
                                <p className="font-medium text-gray-900">View Reports</p>
                                <p className="text-sm text-gray-500">Attendance analytics</p>
                            </div>
                        </Link>
                    </div>
                </div>

                {/* Recent Activity */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {/* Recent Classes */}
                    <div className="bg-white rounded-lg p-6 shadow-sm border">
                        <h2 className="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <School className="h-5 w-5 mr-2 text-blue-600" />
                            {user_role === 'administrator' ? 'Recent Classes' : 'My Classes'}
                        </h2>
                        {recent_classes && recent_classes.length > 0 ? (
                            <div className="space-y-3">
                                {recent_classes.slice(0, 5).map((cls) => (
                                    <Link
                                        key={cls.id}
                                        href={`/classes/${cls.id}`}
                                        className="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 transition-colors"
                                    >
                                        <div>
                                            <p className="font-medium text-gray-900">{cls.name}</p>
                                            <p className="text-sm text-gray-500">
                                                {cls.teacher?.name} • {cls.students_count || 0} students
                                            </p>
                                        </div>
                                        <div className={`px-2 py-1 rounded-full text-xs font-medium ${
                                            cls.is_active 
                                                ? 'bg-green-100 text-green-800' 
                                                : 'bg-gray-100 text-gray-800'
                                        }`}>
                                            {cls.is_active ? 'Active' : 'Inactive'}
                                        </div>
                                    </Link>
                                ))}
                            </div>
                        ) : (
                            <div className="text-center py-8 text-gray-500">
                                <School className="h-12 w-12 mx-auto mb-3 text-gray-300" />
                                <p>No classes found</p>
                                {user_role === 'administrator' && (
                                    <Link
                                        href="/classes/create"
                                        className="text-blue-600 hover:text-blue-800 text-sm mt-2 inline-block"
                                    >
                                        Create your first class
                                    </Link>
                                )}
                            </div>
                        )}
                    </div>

                    {/* Recent Students (Admin only) */}
                    {user_role === 'administrator' && (
                        <div className="bg-white rounded-lg p-6 shadow-sm border">
                            <h2 className="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <Users className="h-5 w-5 mr-2 text-green-600" />
                                Recent Students
                            </h2>
                            {recent_students && recent_students.length > 0 ? (
                                <div className="space-y-3">
                                    {recent_students.slice(0, 5).map((student) => (
                                        <Link
                                            key={student.id}
                                            href={`/students/${student.id}`}
                                            className="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 transition-colors"
                                        >
                                            <div>
                                                <p className="font-medium text-gray-900">
                                                    {student.first_name} {student.last_name}
                                                </p>
                                                <p className="text-sm text-gray-500">
                                                    ID: {student.student_id} • {student.school_class?.name || 'No class assigned'}
                                                </p>
                                            </div>
                                        </Link>
                                    ))}
                                </div>
                            ) : (
                                <div className="text-center py-8 text-gray-500">
                                    <Users className="h-12 w-12 mx-auto mb-3 text-gray-300" />
                                    <p>No students registered</p>
                                    <Link
                                        href="/students/create"
                                        className="text-blue-600 hover:text-blue-800 text-sm mt-2 inline-block"
                                    >
                                        Register your first student
                                    </Link>
                                </div>
                            )}
                        </div>
                    )}

                    {/* Today's Summary for Teachers */}
                    {user_role === 'teacher' && (
                        <div className="bg-white rounded-lg p-6 shadow-sm border">
                            <h2 className="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <Clock className="h-5 w-5 mr-2 text-orange-600" />
                                Today's Summary
                            </h2>
                            <div className="space-y-4">
                                <div className="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                    <div className="flex items-center">
                                        <UserCheck className="h-5 w-5 text-green-600 mr-2" />
                                        <span className="text-sm font-medium text-green-800">Students Present</span>
                                    </div>
                                    <span className="text-lg font-bold text-green-900">{stats.present_today || 0}</span>
                                </div>
                                
                                <div className="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                                    <div className="flex items-center">
                                        <AlertCircle className="h-5 w-5 text-red-600 mr-2" />
                                        <span className="text-sm font-medium text-red-800">Students Absent</span>
                                    </div>
                                    <span className="text-lg font-bold text-red-900">
                                        {(stats.todays_attendance || 0) - (stats.present_today || 0)}
                                    </span>
                                </div>

                                <div className="mt-4 pt-4 border-t">
                                    <Link
                                        href="/attendance/create"
                                        className="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                                    >
                                        <CheckSquare className="h-4 w-4 mr-2" />
                                        Mark Attendance Now
                                    </Link>
                                </div>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}