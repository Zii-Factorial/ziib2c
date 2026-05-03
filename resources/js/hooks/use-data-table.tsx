import { router } from '@inertiajs/react';
import type {
    PaginationState,
    SortingState,
    ColumnFiltersState,
    VisibilityState,
} from '@tanstack/react-table';
import { useState, useEffect } from 'react';

interface UseDataTableProps {
    routePath: string;
    filters: any;
    defaultPerPage?: number;
    searchKey?: string;
}

export function useDataTable({
    routePath,
    filters,
    defaultPerPage = 15,
    searchKey = 'name',
}: UseDataTableProps) {
    // Parse 'field,direction' like 'id,desc' safely
    const initialSorting =
        filters?.sort && typeof filters.sort === 'string'
            ? filters.sort.split(';').map((s: string) => {
                  const [id, dir] = s.split(',');

                  return { id, desc: dir === 'desc' };
              })
            : [];

    const [sorting, setSorting] = useState<SortingState>(initialSorting);
    const [columnFilters, setColumnFilters] = useState<ColumnFiltersState>([]);
    const [columnVisibility, setColumnVisibility] = useState<VisibilityState>(
        {},
    );
    const [rowSelection, setRowSelection] = useState({});

    const [pagination, setPagination] = useState<PaginationState>({
        pageIndex: (filters?.page || 1) - 1, // 0-indexed for table
        pageSize: filters?.limit || defaultPerPage,
    });

    // Parse 'field||operator||value'
    let initialGlobalFilter = '';

    if (filters?.where && typeof filters.where === 'string') {
        const parts = filters.where.split('||');

        if (parts.length === 3 && parts[1] === 'conts') {
            initialGlobalFilter = parts[2];
        }
    }

    const [globalFilter, setGlobalFilter] = useState(initialGlobalFilter);

    // Debounced pushing to URL
    useEffect(() => {
        const handler = setTimeout(() => {
            const query: any = {};

            if (globalFilter) {
                // uses the backend expected format: field||op||val
                query.where = `${searchKey}||conts||${globalFilter}`;
            }

            if (pagination.pageSize !== defaultPerPage) {
                query.limit = pagination.pageSize;
            }

            if (pagination.pageIndex > 0) {
                query.page = pagination.pageIndex + 1; // 1-indexed for backend
            }

            if (sorting.length > 0) {
                // maps to 'id,desc;name,asc'
                query.sort = sorting
                    .map((s) => `${s.id},${s.desc ? 'desc' : 'asc'}`)
                    .join(';');
            }

            // Perform shallow refresh fetching latest datalist bound by parameters
            router.get(routePath, query, {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            });
        }, 500); // 500ms debounce

        return () => clearTimeout(handler);
    }, [
        globalFilter,
        pagination,
        sorting,
        routePath,
        defaultPerPage,
        searchKey,
    ]);

    return {
        sorting,
        setSorting,
        columnFilters,
        setColumnFilters,
        columnVisibility,
        setColumnVisibility,
        rowSelection,
        setRowSelection,
        pagination,
        setPagination,
        globalFilter,
        setGlobalFilter,
    };
}
