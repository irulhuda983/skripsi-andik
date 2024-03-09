export const useFormatJsonToFormData = (json, exceptional = []) => {
    let fm = new FormData();
    for (let x in json) {
        if (Array.isArray(json[x]) || typeof json[x] === "object") {
            for (let y in json[x]) {
                if (json[x][y]) {
                    if (
                        Array.isArray(json[x][y]) ||
                        (typeof json[x][y] === "object" &&
                            exceptional.indexOf(x) == -1)
                    ) {
                        for (let z in json[x][y]) {
                            if (json[x][y][z]) {
                                fm.append(`${x}[${y}][${z}]`, json[x][y][z]);
                            }
                        }
                    } else {
                        fm.append(`${x}[${y}]`, json[x][y]);
                    }
                } else if (json[x][y] === 0) {
                    fm.append(`${x}[${y}]`, json[x][y]);
                }
            }
        } else {
            if (json[x]) {
                fm.append(x, json[x]);
            } else if (json[x] === 0) {
                fm.append(x, json[x]);
            }
        }
    }
    return fm;
};
