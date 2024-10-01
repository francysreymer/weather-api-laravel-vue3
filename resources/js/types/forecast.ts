export type Forecast = {
    date_forecast: string;
    main: string;
    description: string;
    icon: string;
    temperature: number;
    feels_like: number;
    min_temperature: number;
    max_temperature: number;
    pressure: number;
    humidity: number;
    wind_speed: number;
    cloudiness: number;
};
