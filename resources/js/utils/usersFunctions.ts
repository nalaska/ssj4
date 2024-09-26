export function calculateAge(dateOfBirth: string): number {
    const birthDate = new Date(dateOfBirth);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDifference = today.getMonth() - birthDate.getMonth();
    if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    return age;
}

export function formatBelt(belt: string): string {
    if (!belt) {
        return 'Inconnu'; 
    }
    return belt.replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase());
}

