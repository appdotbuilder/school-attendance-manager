import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import { Users, Plus, Search, Filter, Eye, Edit, Trash } from 'lucide-react';
import { useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Students',
        href: '/students',
    },
];

interface Student {
    id: number;
    student_id: string;
    first_name: string;
    last_name: string;
    date_of_birth: string;
    gender: string;
    parent_name?: string;
    parent_phone?: string;
    parent_email?: string;
    status: string;
    school_class?: {
        id: number;
        name: string;
        teacher?: {
            name: string;
        };
    };
}

interface SchoolClass {
    id: number;
    name: string;
    teacher?: {
        name: string;
    };
}

interface Props {
    students: {
        data: Student[];
        links: Array<{
            url?: string;
            label: string;
            active: boolean;
        }>;
        current_page: number;
        last_page: number;
        total: number;
    };
    classes: SchoolClass[];
    filters: {
        search?: string;
        class_id?: string;
        status?: string;
    };
    [key: string]: unknown;
}

export default function StudentIndex({ students, classes, filters }: Props) {
    const [searchTerm, setSearchTerm] = useState(filters.search || '');
    const [selectedClass, setSelectedClass] = useState(filters.class_id || '');
    const [selectedStatus, setSelectedStatus] = useState(filters.status || '');

    const handleSearch = () => {
        router.get('/students', {
            search: searchTerm,
            class_id: selectedClass,
            status: selectedStatus,
        }, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const handleReset = () => {
        setSearchTerm('');
        setSelectedClass('');
        setSelectedStatus('');
        router.get('/students', {}, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const getStatusBadgeClass = (status: string) => {
        switch (status) {
            case 'active':
                return 'bg-green-100 text-green-800';
            case 'inactive':
                return 'bg-gray-100 text-gray-800';
            case 'transferred':
                return 'bg-blue-100 text-blue-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Students" />
            <div className="p-6 space-y-6">
                {/* Header */}
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900 flex items-center">
                            <Users className="h-7 w-7 mr-2 text-blue-600" />
                            Students
                        </h1>
                        <p className="text-gray-600 mt-1">
                            Manage student registrations and information
                        </p>
                    </div>
                    <Link
                        href="/students/create"
                        className="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        <Plus className="h-4 w-4 mr-2" />
                        Add Student
                    </Link>
                </div>

                {/* Filters */}
                <div className="bg-white rounded-lg p-6 shadow-sm border">
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Search Students
                            </label>
                            <div className="relative">
                                <Search className="h-4 w-4 absolute left-3 top-3 text-gray-400" />
                                <input
                                    type="text"
                                    placeholder="Name or Student ID..."
                                    value={searchTerm}
                                    onChange={(e) => setSearchTerm(e.target.value)}
                                    className="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    onKeyPress={(e) => e.key === 'Enter' && handleSearch()}
                                />
                            </div>
                        </div>

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Class
                            </label>
                            <select
                                value={selectedClass}
                                onChange={(e) => setSelectedClass(e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="">All Classes</option>
                                {classes.map((cls) => (
                                    <option key={cls.id} value={cls.id}>
                                        {cls.name} - {cls.teacher?.name}
                                    </option>
                                ))}
                            </select>
                        </div>

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Status
                            </label>
                            <select
                                value={selectedStatus}
                                onChange={(e) => setSelectedStatus(e.target.value)}
                                className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="transferred">Transferred</option>
                            </select>
                        </div>

                        <div className="flex items-end space-x-2">
                            <button
                                onClick={handleSearch}
                                className="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                            >
                                <Filter className="h-4 w-4 mr-2" />
                                Filter
                            </button>
                            <button
                                onClick={handleReset}
                                className="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                            >
                                Reset
                            </button>
                        </div>
                    </div>
                </div>

                {/* Students List */}
                <div className="bg-white rounded-lg shadow-sm border overflow-hidden">
                    {students.data.length > 0 ? (
                        <>
                            <div className="overflow-x-auto">
                                <table className="w-full">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Student
                                            </th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Class
                                            </th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Contact
                                            </th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {students.data.map((student) => (
                                            <tr key={student.id} className="hover:bg-gray-50">
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div>
                                                        <div className="text-sm font-medium text-gray-900">
                                                            {student.first_name} {student.last_name}
                                                        </div>
                                                        <div className="text-sm text-gray-500">
                                                            ID: {student.student_id}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    {student.school_class ? (
                                                        <div>
                                                            <div className="text-sm font-medium text-gray-900">
                                                                {student.school_class.name}
                                                            </div>
                                                            <div className="text-sm text-gray-500">
                                                                {student.school_class.teacher?.name}
                                                            </div>
                                                        </div>
                                                    ) : (
                                                        <span className="text-sm text-gray-500">No class assigned</span>
                                                    )}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div>
                                                        {student.parent_name && (
                                                            <div className="text-sm text-gray-900">{student.parent_name}</div>
                                                        )}
                                                        {student.parent_phone && (
                                                            <div className="text-sm text-gray-500">{student.parent_phone}</div>
                                                        )}
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span className={`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getStatusBadgeClass(student.status)}`}>
                                                        {student.status}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <div className="flex items-center space-x-2">
                                                        <Link
                                                            href={`/students/${student.id}`}
                                                            className="text-blue-600 hover:text-blue-800"
                                                            title="View Details"
                                                        >
                                                            <Eye className="h-4 w-4" />
                                                        </Link>
                                                        <Link
                                                            href={`/students/${student.id}/edit`}
                                                            className="text-green-600 hover:text-green-800"
                                                            title="Edit Student"
                                                        >
                                                            <Edit className="h-4 w-4" />
                                                        </Link>
                                                        <button
                                                            onClick={() => {
                                                                if (confirm('Are you sure you want to delete this student?')) {
                                                                    router.delete(`/students/${student.id}`);
                                                                }
                                                            }}
                                                            className="text-red-600 hover:text-red-800"
                                                            title="Delete Student"
                                                        >
                                                            <Trash className="h-4 w-4" />
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>

                            {/* Pagination */}
                            {students.last_page > 1 && (
                                <div className="px-6 py-3 border-t border-gray-200 bg-gray-50">
                                    <div className="flex items-center justify-between">
                                        <div className="text-sm text-gray-700">
                                            Showing {((students.current_page - 1) * 10) + 1} to {Math.min(students.current_page * 10, students.total)} of {students.total} results
                                        </div>
                                        <div className="flex items-center space-x-2">
                                            {students.links.map((link, index) => (
                                                <button
                                                    key={index}
                                                    onClick={() => link.url && router.get(link.url)}
                                                    disabled={!link.url}
                                                    className={`px-3 py-1 text-sm rounded ${
                                                        link.active
                                                            ? 'bg-blue-600 text-white'
                                                            : link.url
                                                            ? 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'
                                                            : 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                                    }`}
                                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                                />
                                            ))}
                                        </div>
                                    </div>
                                </div>
                            )}
                        </>
                    ) : (
                        <div className="text-center py-12">
                            <Users className="h-16 w-16 mx-auto mb-4 text-gray-300" />
                            <h3 className="text-lg font-medium text-gray-900 mb-2">No students found</h3>
                            <p className="text-gray-500 mb-6">
                                {Object.values(filters).some(f => f) 
                                    ? 'Try adjusting your search filters.' 
                                    : 'Get started by registering your first student.'
                                }
                            </p>
                            <Link
                                href="/students/create"
                                className="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                            >
                                <Plus className="h-4 w-4 mr-2" />
                                Add Student
                            </Link>
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}