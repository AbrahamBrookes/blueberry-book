export interface TableColumn {
    key: string;
    label: string;
    visible?: boolean;
    sortable?: boolean;
    width?: string;
    align?: 'left' | 'center' | 'right';
}

export interface SortConfig {
    column: string;
    direction: 'asc' | 'desc';
}
