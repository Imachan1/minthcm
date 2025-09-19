import { MintApi } from './api'

class CandidaturesApi extends MintApi {
    public async convert(userType: string, candidatureId: string, userName: string | null) { // CR: camelCase userType/CandidatureId
        return await this.instance.post('Candidatures/convert', {
            usertype: userType,
            candidature_id: candidatureId,
            username: userName,
        })
    }
}

export const candidaturesApi = new CandidaturesApi()
