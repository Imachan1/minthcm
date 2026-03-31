const standardizationTable = [
    { from: 'ä', to: 'a' },
    { from: 'ö', to: 'o' },
    { from: 'ü', to: 'u' },
    { from: 'ß', to: 'ss' },
    { from: 'ą', to: 'a' },
    { from: 'ć', to: 'c' },
    { from: 'ę', to: 'e' },
    { from: 'ł', to: 'l' },
    { from: 'ó', to: 'o' },
    { from: 'ś', to: 's' },
    { from: 'ń', to: 'n' },
    { from: 'ź', to: 'z' },
    { from: 'ż', to: 'z' }
]
  
export const standardizeText = text => {
    if (!text) {
        return ''
    }
    let standardized = text.toLowerCase().trim()
    standardizationTable.forEach(rule => {
        standardized = standardized.replaceAll(rule.from, rule.to)
    })
    return standardized
}
