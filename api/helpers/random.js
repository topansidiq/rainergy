export function getRandomValue(min = 0.01, max = 0.3) {
    return parseFloat((Math.random() * (max - min) + min).toFixed(3));
}