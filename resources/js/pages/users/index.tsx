import { Head } from '@inertiajs/react';
import type { ColumnDef } from '@tanstack/react-table';
import { MoreHorizontal, ArrowUpDown } from 'lucide-react';
import React, { useMemo } from 'react';
import { Button } from '@/components/ui/button';
import { DataTable } from '@/components/ui/data-table';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { useDataTable } from '@/hooks/use-data-table';
import usersRoutes from '@/routes/users';

// This defines the Data structure that backend Spatie data provides
type User = {
    id: number;
    name: string;
    email: string;
    created_at: string;
};

interface UsersPageProps {
    users: {
        data: User[];
        meta: {
            total: number;
        };
    };
    filters: any;
}

export default function UsersIndex({ users, filters }: UsersPageProps) {
    // Configured columns supporting standard sorts, actions
    const columns = useMemo<ColumnDef<User>[]>(
        () => [
            {
                accessorKey: 'id',
                header: 'ID',
                cell: ({ row }) => (
                    <div className="w-[80px]">{row.getValue('id')}</div>
                ),
                enableSorting: true,
            },
            {
                accessorKey: 'name',
                header: ({ column }) => {
                    return (
                        <Button
                            variant="ghost"
                            onClick={() =>
                                column.toggleSorting(
                                    column.getIsSorted() === 'asc',
                                )
                            }
                        >
                            Name
                            <ArrowUpDown className="ml-2 h-4 w-4" />
                        </Button>
                    );
                },
            },
            {
                accessorKey: 'email',
                header: 'Email',
            },
            {
                id: 'actions',
                cell: ({ row }) => {
                    const user = row.original;

                    return (
                        <DropdownMenu>
                            <DropdownMenuTrigger asChild>
                                <Button variant="ghost" className="h-8 w-8 p-0">
                                    <span className="sr-only">Open menu</span>
                                    <MoreHorizontal className="h-4 w-4" />
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end">
                                <DropdownMenuLabel>Actions</DropdownMenuLabel>
                                <DropdownMenuItem
                                    onClick={() =>
                                        navigator.clipboard.writeText(
                                            user.email,
                                        )
                                    }
                                >
                                    Copy user email
                                </DropdownMenuItem>
                                <DropdownMenuSeparator />
                                <DropdownMenuItem>
                                    View details
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    );
                },
            },
        ],
        [],
    );

    // Utilize our custom hook seamlessly routing Inertia events out of Tanstack states
    const tableState = useDataTable({
        routePath: usersRoutes.index().url,
        filters: filters,
        defaultPerPage: 15,
    });

    return (
        <div className="container mx-auto space-y-6 py-10">
            <Head title="Users Manager" />
            <div>
                <h2 className="text-3xl font-bold tracking-tight">Users</h2>
                <p className="text-muted-foreground">
                    Manage system users natively configured via Shadcn and
                    TanStack Data Table.
                </p>
            </div>

            <DataTable
                columns={columns}
                data={users?.data || []}
                totalItems={users?.meta?.total || 0}
                tableState={tableState}
            />
        </div>
    );
}
