import type { Customer } from '@models/Customer';

export interface Contact {
    id: number;
    first_name: string;
    last_name: string;
    created_at: string;
    updated_at: string;

    // Relationships (when loaded)
    customers?: Customer[];
}
