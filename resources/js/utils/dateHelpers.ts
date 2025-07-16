/**
 * Format an ISO date string or Date object to YYYY-MM-DD format for HTML date inputs
 * @param date - ISO date string, Date object, or null/undefined
 * @returns Formatted date string in YYYY-MM-DD format or empty string if invalid
 */
export function formatISOStringDate(date: string | Date | null | undefined): string {
    if (!date) {
        return '';
    }

    try {
        const dateObj = typeof date === 'string' ? new Date(date) : date;

        // Check if the date is valid
        if (isNaN(dateObj.getTime())) {
            return '';
        }

        // Format to YYYY-MM-DD
        const year = dateObj.getFullYear();
        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
        const day = String(dateObj.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    } catch (error) {
        console.warn('Error formatting date:', error);
        return '';
    }
}
