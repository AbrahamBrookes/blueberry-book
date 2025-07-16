import type { CustomerCategory } from '@models/CustomerCategory';
import type { Contact } from '@models/Contact';

export interface Customer {
    id: number;
    name: string;
    reference?: string;
    started_at?: string;
    description?: string;
    customer_category_id: number;
    status: string;
    created_at: string;
    updated_at: string;

    // Relationships (when loaded)
    customer_category?: CustomerCategory;
    contacts?: Contact[];
}
