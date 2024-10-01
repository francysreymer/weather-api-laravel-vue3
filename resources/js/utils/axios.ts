import axios from 'axios';

export const handleAxiosError = (error: any): string[] => {
    const errorMessages: string[] = [];

    if (axios.isAxiosError(error) && error.response) {
        if (error.response.data.errors) {
            Object.values(error.response.data.errors).forEach((err: any) => {
                errorMessages.push(err);
            });
        } else if (error.response.data.error) {
            errorMessages.push(error.response.data.error);
        } else if (error.response.data.message) {
            errorMessages.push(error.response.data.message);
        } else {
            errorMessages.push('An unexpected error occurred.');
        }
    } else {
        errorMessages.push('An unexpected error occurred.');
    }

    return errorMessages;
};
